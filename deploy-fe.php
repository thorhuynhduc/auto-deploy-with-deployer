<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'Abc');

// Project repository
set('repository', 'git@git.vn:dng.pj0026.ahn.git');

set('default_timeout', 3600);

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

localhost()
    ->set('deploy_path', '/var/www/html/source/web')
    ->set('writable_mode', 'chmod');
    
task('deploy:npm-install', function () {
    runLocally('cd {{release_path}} && npm install');
});

// Thực hiện các task để triển khai ứng dụng
task('deploy:npm-build', function () {
    runLocally('cd {{release_path}} && npm run build:prod');
});

task('deploy:reload-nginx', function () {
    run('nginx -s reload');
});

before('deploy:update_code', 'deploy:fix-git-issue');
task('deploy:fix-git-issue', function () {
    runLocally('eval $(ssh-agent -s)');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

desc('Deploy Web project');
task('deploy', [
    'deploy:prepare',
    'deploy:npm-install',
    'deploy:npm-build',
    'deploy:symlink',
    'deploy:reload-nginx',
]);

// vendor/bin/dep --file=deploy-user-web.php deploy --branch=develop
