<?php
namespace Deployer;

require_once __DIR__ . '/laravel.php';

add('recipes', ['dojo']);

task('dojo:provision', [
    'dojo:provision:setup',
    'provision',
    'dojo:provision:done',
]);

task('dojo:provision:setup', function (){
    writeln('Starting up');
    set('remote_user', 'root');
});

task('dojo:provision:done', function (){
    info('Getting SSH Key.');
    set('remote_user', 'deployer');
    $ssh_key = run("cat ~/.ssh/id_rsa.pub");

    info('If this is your first time provisioning this server, you should add ssh key to your github/bitbucket account.');
    info('Run: php blacksmith dojo:ssh_key');
});

task('dojo:ssh_key', function (){
    info('Getting SSH Key.');
    set('remote_user', 'deployer');
    $ssh_key = run("cat ~/.ssh/id_rsa.pub");

    echo "<fg=black;bg=bright-green>$ssh_key</>";
});

task('dojo:reset_known_hosts', function (){
    runLocally("echo '' > ~/.ssh/known_hosts");
});

task('dojo:upload', function (){
    $file_path = ask('Enter file name');

    dojo_upload($file_path);
});

desc('Upload keywords.txt');
task('dojo:upload_keywords_txt', function (){
    $file_path = 'keywords.txt';

    dojo_upload($file_path);
});

desc('Upload .env');
task('dojo:upload_env', function (){
    $file_path = '.env';
    dojo_upload($file_path);
});

desc('npm install & npm run build');
task('deploy:npm', function () {
    if(!file_exists('package.json')){
        info('package.json does not exist, skipping npm install');
        return false;
    }

    run('cd {{release_or_current_path}} && npm install && npm run build');
});

desc('deploy sqlite');
task('dojo:deploy_sqlite', function () {
    if (!test('[ -d {{release_or_current_path}}/database ]')) {
        run('mkdir {{release_or_current_path}}/database');
    }

    if(!test('[ -f {{release_or_current_path}}/database/database.sqlite ]')) {
        run('touch {{release_or_current_path}}/database/database.sqlite');
    }

    if(test('[ -f {{release_or_current_path}}/database/database.sqlite ]')) {
        info('Database exists, let\'s make it writable');
        run('cd {{release_or_current_path}} && chmod 777 database/database.sqlite');
    }
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
    'dojo:deploy_sqlite',
    'artisan:migrate',
    'deploy:publish',
]);
