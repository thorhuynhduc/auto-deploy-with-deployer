<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'Carbonix');

// Project repository
set('repository', 'git@git.lab.com.vn:abc.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', ['storage']);

// Writable dirs by web server
add('writable_dirs', []);

// Hosts
//host('deploy-api')
//    ->set('deploy_path', '/var/www/html/api')
//    ->set('writable_mode', 'chmod');

localhost()
    ->set('deploy_path', '/var/www/html/source/api')
    ->set('writable_mode', 'chmod');

task('deploy:reload-nginx', function () {
    run('nginx -s reload');
});

before('deploy:update_code', 'deploy:fix-git-issue');
task('deploy:fix-git-issue', function () {
    runLocally('eval $(ssh-agent -s)');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

before('deploy:symlink', 'artisan:migrate');
after('deploy:symlink', 'deploy:reload-nginx');

// Tasks
desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:view:cache',
    'deploy:publish',
    'deploy:reload-nginx'
]);

// vendor/bin/dep --file=deploy.php deploy
