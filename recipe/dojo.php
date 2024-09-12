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

    info('If this is your first time provisioning this server, you should add ssh key to your github/bitbucket account. Run: php blacksmith dojo:ssh_key');
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