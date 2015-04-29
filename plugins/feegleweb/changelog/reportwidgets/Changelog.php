<?php namespace Feegleweb\Changelog\ReportWidgets;

use Lang;
use Markdown;
use Backend\Classes\ReportWidgetBase;
use October\Rain\Network\Http;
use System\Models\Parameters;
use ApplicationException;
use SystemException;
use Exception;

class Changelog extends ReportWidgetBase
{

    public function render()
    {
        try {
            $this->loadData();
        } catch (Exception $e) {
            $this->vars['error'] = $e->getMessage();
        }

        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'backend::lang.dashboard.widget_title_label',
                'default'           => 'feegleweb.changelog::lang.log.widget_title',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ],
            'recentLogs' => [
                'title'             => 'feegleweb.changelog::lang.recentLogs.label',
                'description'       => 'feegleweb.changelog::lang.recentLogs.description',
                'default'           => 5,
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'feegleweb.changelog::lang.recentLogs.validation_message',
            ],
        ];
    }

    public function loadData()
    {
        $this->checkPermissions();
        $this->loadBuildNum();
        $this->loadChangelog();

        $this->vars['current'] = $this->build;
        $this->vars['behind'] = $this->countBuildsBehind();

        $log = $this->changelog['precise'] ?: $this->changelog['recent'];

        $this->vars['detail'] = Markdown::parse($log);
    }

    protected function checkPermissions()
    {
        if ($this->controller->user->hasAccess('system.manage_updates')) {
            return;
        }

        throw new ApplicationException(Lang::get('feegleweb.changelog::lang.app.permission_error'));
    }

    protected function loadBuildNum()
    {
        $this->build = Parameters::get('system::core.build');
    }

    protected function countBuildsBehind()
    {
        return substr_count($this->changelog['precise'], '* **Build ');
    }

    protected function loadChangelog()
    {
        $uri = 'https://raw.githubusercontent.com/octobercms/october/master/CHANGELOG.md';

        $log = Http::get($uri);
        if ($log == '' || $log->code !== 200) {
            throw new SystemException(sprintf(Lang::get('feegleweb.changelog::lang.log.load_error'), $uri));
        }

        $this->changelog = [
            'precise' => $this->slicePrecise($log),
            'recent'  => $this->sliceRecent($log),
        ];
    }

    protected function slicePrecise($data)
    {
        $build = $this->build;
        $foundBuild = false;

        // Find the nearest older build to the current one, not all are on changelog.
        // Build 64 was the first public release, so don't go past it
        while (!$foundBuild && $build >= 64) {
            $pos = strpos($data, "* **Build {$build}**");

            $pos === false ? $build-- : $foundBuild = true;
        }

        if (!$foundBuild) {
            throw new ApplicationException(
                sprintf(Lang::get('feegleweb.changelog::lang.log.slice_error'), $this->build)
            );
        }

        return substr($data, 0, $pos);
    }

    protected function sliceRecent($data)
    {
        $allBuilds  = substr_count($data, "* **Build ");
        $showBuilds = $this->property('recentLogs');
        $entryCount = 0;

        // Don't bother slicing if they want everything
        if ((int)$showBuilds >= $allBuilds) {
            return $data;
        }

        while ($entryCount <= $showBuilds) {
            $offset = isset($pos) ? $pos + 1 : 0;
            $pos = strpos($data, "* **Build ", $offset);

            if ($pos !== false) {
                $entryCount++;
            }
        }

        return substr($data, 0, $pos);
    }
}
