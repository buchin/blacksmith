<?php
namespace Deployer;

require_once __DIR__ . '/dojo.php';

add('recipes', ['autopost']);

add('writable_dirs', [
    'database'
]);


desc('npm install & npm run build');
task('deploy:npm', function () {
    run('cd {{release_or_current_path}} && npm install && npm run build');
});


/**
 * Main deploy task.
 */
desc('Deploys your project');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'dojo:upload_env',
    'deploy:npm',
    'artisan:storage:link',
    'artisan:migrate',
    'deploy:publish',
]);