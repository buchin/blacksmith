<?php
namespace Deployer;

require_once __DIR__ . '/dojo.php';

add('recipes', ['multistatic']);

add('shared_dirs', [
    'sites'
]);