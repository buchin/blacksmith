<?php

declare(strict_types=1);

namespace Deployer;

set('domain', function () {
    return ask(' Domain: ');
});

set('public_path', function () {
    return ask(' Public path: ', 'public');
});

desc('Provision website');
task('provision:website', function () {
    set('remote_user', 'deployer');
    run("[ -d {{deploy_path}} ] || mkdir -p {{deploy_path}}");

    set('deploy_path', run("realpath {{deploy_path}}"));
    cd('{{deploy_path}}');

    run("[ -d log ] || mkdir log");
    run("chgrp caddy log");

    $caddyfile = parse(file_get_contents(__DIR__ . '/Caddyfile'));

    if (test('[ -f Caddyfile ]')) {
        run("echo $'$caddyfile' > Caddyfile.new");
        $diff = run('diff -U5 --color=always Caddyfile Caddyfile.new', ['no_throw' => true]);
        if (empty($diff)) {
            run('rm Caddyfile.new');
        } else {
            info('Found Caddyfile changes');
            writeln("\n" . $diff);
            $answer = askChoice(' Which Caddyfile to save? ', ['old', 'new'], 0);
            if ($answer === 'old') {
                run('rm Caddyfile.new');
            } else {
                run('mv Caddyfile.new Caddyfile');
            }
        }
    } else {
        run("echo $'$caddyfile' > Caddyfile");
    }

    set('remote_user', 'root');

    if (test("grep -q ':80' /etc/caddy/Caddyfile")) {
        run("echo '' > /etc/caddy/Caddyfile");
    }

    if (!test("grep -q 'import {{deploy_path}}/Caddyfile' /etc/caddy/Caddyfile")) {
        run("echo 'import {{deploy_path}}/Caddyfile' >> /etc/caddy/Caddyfile");
    }

    run('caddy fmt --overwrite /etc/caddy/Caddyfile');
    run('caddy reload --config /etc/caddy/Caddyfile');

    info("Website {{domain}} configured!");
})->limit(1);

desc('Shows caddy logs');
task('logs:caddy', function () {
    run('tail -f {{deploy_path}}/log/access.log');
})->verbose();

desc('Shows caddy syslog');
task('logs:caddy:syslog', function () {
    run('sudo journalctl -u caddy -f');
})->verbose();
