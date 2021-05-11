<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/slack.php';

// Project name
set('application', 'api-webhook');

// Project repository
set('repository', 'git@github.com:frf/webhook-bitbucket-to-discord.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', ['.env']);
add('shared_dirs', ['storage']);

// Writable dirs by web server
add('writable_dirs', [
    'bootstrap/cache',
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);

// Hosts
host('104.131.170.111')
    ->hostname('104.131.170.111')
    ->user('webhook')
    ->set('deploy_path', '/var/www/webhook');

task('build', function () {
    run('cd {{release_path}} && build');
});

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link',
    'artisan:view:clear',
    'artisan:optimize:clear',
    'deploy:symlink',
    'artisan:migrate',
    'deploy:unlock',
    'cleanup',
]);

after('deploy', 'success');
after('deploy:failed', 'deploy:unlock');


