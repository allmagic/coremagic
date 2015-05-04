module.exports = function (shipit) {
  require('shipit-deploy')(shipit);

  shipit.initConfig({
    default: {
      workspace: '/tmp/github-monitor',
      deployTo: '/tmp/deploy_to',
      repositoryUrl: 'git@github.com:allmagic/coremagic.git',
      ignores: ['.git', 'node_modules'],
      rsync: ['--del'],
      keepReleases: 2,
      key: '/path/to/key',
      shallowClone: true
    },
    staging: {
      servers: 'root@128.199.139.175'
    }
  });

  // RUN chown -R www-data:www-data
};
