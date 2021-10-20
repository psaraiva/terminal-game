<?php

declare(strict_types=1);

namespace App;

require 'vendor/autoload.php';

$param = new GameParam();
$game = new Game($param->apply($argv)->toArray(), new Log(Log::OUTPUT_FILE));
$game->run();
