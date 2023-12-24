<?php

declare(strict_types=1);

namespace App;

require 'vendor/autoload.php';

$params = Command::applyArgs($argv);
try {
    $game = new Game($params);
    $game->run();
} catch (\Exception $e) {
    echo sprintf("[ERROR] %s".PHP_EOL."(O_o)", $e->getMessage());
}
