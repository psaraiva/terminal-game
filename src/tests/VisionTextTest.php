<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\VisionText;

class VisionTextTest extends TestCase
{
    public function testLine()
    {
        $expected = "+-------------------------------------------------+";
        $this->assertEquals($expected, VisionText::LINE);
    }

    public function testDisplayLine()
    {
        $expected = VisionText::LINE . PHP_EOL;
        $this->assertEquals($expected, VisionText::displayLine());
    }

    public function testFormatLine()
    {
        $expected = "Unit Test is essential                  |".PHP_EOL;
        $this->assertEquals($expected, VisionText::formatLine('Unit Test is essential', 40));
    }

    public function testDisplayHeader()
    {
        $expected  = "+-------------------------------------------------+".PHP_EOL;
        $expected .= "| Game - Discovery the number. [mode:easy]|".PHP_EOL;
        $expected .= "| The number have range 10 - 50.        |".PHP_EOL;
        $expected .= "| You have 5 chance for discovery the number.|".PHP_EOL;

        $this->assertEquals($expected, VisionText::displayHeader('easy', 10, 50, 5, 40));
    }

    public function testDisplayTrick()
    {
        $trickMessage = [
            "| The number is > 26.                             |".PHP_EOL
        ];

        $expected  = "| Trick.                                |".PHP_EOL;
        $expected .= "+-------------------------------------------------+".PHP_EOL;
        $expected .= "| The number is > 26.                             |".PHP_EOL;
        $expected .= "+-------------------------------------------------+".PHP_EOL;

        $this->assertEquals($expected, VisionText::displayTrick($trickMessage, 40));
    }

    public function testMountTrickMessage()
    {
        $expected = "| The number is > 10.                   |".PHP_EOL;
        $this->assertEquals($expected, VisionText::mountTrickMessage(10, '>', 40));
    }

    public function testDisplayDebug()
    {
        $expected  = "+-------------------------------------------------+".PHP_EOL;
        $expected .= "| Debug: number=41, lives=5             |".PHP_EOL;
        $expected .= "+-------------------------------------------------+".PHP_EOL;

        $this->assertEquals($expected, VisionText::displayDebug(41, 5, 40));
    }

    public function testDisplayGameOver()
    {
        $expected  = "+-------------------------------------------------+".PHP_EOL;
        $expected .= "| Game Over.                            |".PHP_EOL;
        $expected .= "+-------------------------------------------------+".PHP_EOL;
        $expected .= "| Game over! The number is 5.           |".PHP_EOL;
        $expected .= "+-------------------------------------------------+".PHP_EOL;

        $this->assertEquals($expected, VisionText::displayGameOver("Game over!", 5, 40));
    }
}
