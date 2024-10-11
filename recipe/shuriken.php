<?php
namespace Deployer;

require_once __DIR__ . '/composer.php';

add('recipes', ['shuriken']);

add('writable_dirs', ['exports']);