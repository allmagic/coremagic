<?php namespace Ebussola\Userfacebook\Components;

use eBussola\Userfacebook\Models\Settings;
use eBussola\Userfacebook\Models\SocialIds;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphObject;
use Auth;
use RainLab\User\Components\Session;
use RainLab\User\Models\User;
use Request;
use Redirect;

class FacebookSession extends Session
{

    public $appId;

    public function componentDetails()
    {
        return [
            'name'        => 'Facebook Session',
            'description' => \Lang::get('ebussola.userfacebook::lang.facebook_session.description')
        ];
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun() {
        $this->appId = Settings::get('app_id');

        return parent::onRun();
    }

    public function onLoginWithFacebook() {
        $fb_js = new FacebookJavaScriptLoginHelper();
        $response = (new FacebookRequest($fb_js->getSession(), 'GET', '/me'))->execute();
        /** @var GraphObject $user */
        $fb_user = $response->getGraphObject();

        $social_ids = SocialIds::where('facebook_id', $fb_user->getProperty('id'))->first();
        if (!$social_ids) {

	        $user = User::where( 'email', $fb_user->getProperty( 'email' ) )->first();
	        if (!$user) {
		        $password = uniqid();
		        $user = Auth::register([
			        'name' => $fb_user->getProperty('first_name'),
                    'surname' => $fb_user->getProperty('last_name'),
			        'email' => $fb_user->getProperty('email'),
			        'username' => $fb_user->getProperty('email'),
			        'password' => $password,
			        'password_confirmation' => $password
		        ], true);
	        }

            $social_ids = new SocialIds();
            $social_ids->user_id = $user->id;
            $social_ids->facebook_id = $fb_user->getProperty('id');
            $social_ids->save();

        } else {
            $user = $social_ids->user;
        }

        Auth::login($user, true);

        $url = post('redirect', Request::fullUrl());
        return Redirect::to($url);
    }

}