module.exports = function (shipit) {
    require('shipit-deploy')(shipit);

    shipit.initConfig({
        default: {
            workspace: '/tmp/github-monitor',
            deployTo: '/usr/share/nginx/html/shipit',
            repositoryUrl: 'git@github.com:allmagic/coremagic.git',
            ignores: ['.git', 'node_modules'],
            rsync: ['--del'],
            keepReleases: 2,
            shallowClone: true
        },
        staging: {
            servers: 'root@119.81.131.29'
        }
    });

    shipit.on('updated', function () {

    });

    shipit.on('published', function () {

        setTimeout(function () {
            shipit.remote('cp /root/coremagic_config/* /usr/share/nginx/html/shipit/current/config/')
                .then(function () {
                    shipit.remote('chown -R www-data:www-data /usr/share/nginx/html/shipit');
                }).then(function () {
                    //shipit.remote('php /usr/share/nginx/html/shipit/current/artisan october:update');
                });

        }, 2000);
    });

    shipit.on('cleaned', function () {
        setTimeout(function () {
            shipit.remote('php /usr/share/nginx/html/shipit/current/artisan clear-compiled')
                .then(function () {
                    shipit.remote('php /usr/share/nginx/html/shipit/current/artisan route:clear');
                    shipit.remote('php /usr/share/nginx/html/shipit/current/artisan cache:clear');
                    shipit.remote('php /usr/share/nginx/html/shipit/current/artisan optimize --force');
                });
        }, 2000);
    });

};
