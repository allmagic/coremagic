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
      servers: 'root@128.199.139.175'
    }
  });

  shipit.on('updated', function () {
    shipit.remote('chown -R www-data:www-data /usr/share/nginx/html/shipit');
  });

  shipit.on('published', function () {
    shipit.remote('cp /root/coremagic_config/* /usr/share/nginx/html/shipit/current/config/');
    shipit.remote('chown -R www-data:www-data /usr/share/nginx/html/shipit');
    shipit.remote('php /usr/share/nginx/html/shipit/current/artisan route:clear');
    shipit.remote('php /usr/share/nginx/html/shipit/current/artisan clear-compiled');
    shipit.remote('php /usr/share/nginx/html/shipit/current/artisan optimize');
  });

};
