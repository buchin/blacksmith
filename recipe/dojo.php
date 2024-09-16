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

