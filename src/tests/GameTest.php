<?php

use App\Game;

test('example', function () {

    $game = new Game([]);
    $game->run();

    expect(true)->toBeTrue();
});
