<?php
namespace Deployer;

task('dojo:provision', [
    'dojo:provision:setup',
    'provision',
    'dojo:provision:done',
]);

task('dojo:provision:setup', function (){
    writeln('
   __   __         __              _ __  __ 
  / /  / /__ _____/ /__ ___ __ _  (_) /_/ / 
 / _ \/ / _ `/ __/  \'_/(_-</  \' \/ / __/ _ \
/_.__/_/\_,_/\__/_/\_\/___/_/_/_/_/\__/_//_/
==== https://dojo.cc/blacksmith-deployer ====
    ');
    writeln('Starting up');
    set('remote_user', 'root');
});

task('dojo:provision:done', function (){
    info('Getting SSH Key.');
    set('remote_user', 'deployer');
    $ssh_key = run("cat ~/.ssh/id_rsa.pub");

    info('If this is your first time provisioning this server, add this ssh key to your github/bitbucket account: ' . $ssh_key);
});