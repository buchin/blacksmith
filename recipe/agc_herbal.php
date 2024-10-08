<?php
namespace Deployer;

require_once __DIR__ . '/dojo.php';

add('recipes', ['agc_herbal']);

add('shared_dirs', ['public/images']);

desc('Run AGC Herbal scraper');
task('agc_herbal:scrape', function () {
    set('default_timeout', null);
    run('cd {{release_or_current_path}} && php artisan agc_herbal:scrape');
})->verbose();