module.exports = function (shipit) {
  require('shipit-deploy')(shipit);

  shipit.initConfig({
    default: {
      workspace: '/tmp/github-monitor',
      deployTo: '/root/shipit',
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
    shipit.remote('chown -R www-data:www-data /root/shipit');
    shipit.remote('cp /root/coremagic_config/* /root/shipit/current/config/');
  });

  // RUN chown -R www-data:www-data
};
