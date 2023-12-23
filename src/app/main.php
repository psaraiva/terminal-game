<?php

declare(strict_types=1);

namespace App;

require 'vendor/autoload.php';

$params = Command::applyArgs($argv);

$game = new Game($params);
$game->run();
