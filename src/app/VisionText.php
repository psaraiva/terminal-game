<?php

declare(strict_types=1);

namespace App;

/**
 * Class VisionText, representation of text to terminal.
 * @package App
 */
final class VisionText {

    /**
     * Line to terminal.
     */
    public const LINE = '+-------------------------------------------------+';

    /**
     * Clear terminal.
     * @return void
     */
    public static function clear(): void
    {
        system('clear');
    }

    // @todo: Enable load line dynamic. +.(-*n).+
    /**
     * Display line to terminal.
     * @return string
     */
    public static function displayLine(): string
    {
        return static::LINE . PHP_EOL;
    }

    /**
     * Format line to terminal.
     * @param string $text Text to print.
     * @param int $lineLength Line length to terminal.
     * @return string
     */
    public static function formatLine(string $text, int $lineLength): string
    {
        $text = str_pad($text, $lineLength);
        return sprintf("%s|%s", $text, PHP_EOL);
    }

    /**
     * Display header message.
     * @param string $mode Mode of game.
     * @param int $min Min number to discovery.
     * @param int $max Max number to discovery.
     * @param int $lives Lives to discovery number.
     * @param int $lineLength Line length to terminal.
     * @return string
     */
    public static function displayHeader(string $mode, int $min, int $max, int $lives, int $lineLength): string
    {
        $text = VisionText::displayLine();
        $text .= VisionText::formatLine(sprintf("| Game - Discovery the number. [mode:%s]", $mode), $lineLength);
        $text .= VisionText::formatLine(sprintf("| The number have range %d - %d.", $min, $max), $lineLength);
        $text .= VisionText::formatLine(sprintf("| You have %d chance for discovery the number.", $lives), $lineLength);
        return $text;
    }

    // @todo: Verify the trickMessage most be formated before...
    /**
     * Display trick message.
     * @param int $trickMessage collection of message trick.
     * @param int $lineLength Line length to terminal.
     * @return string
     */
    public static function displayTrick(array $trickMessage, int $lineLength): string
    {
        $text .= VisionText::formatLine('| Trick.', $lineLength);
        $text .= VisionText::displayLine();

        // @todo: Use array explode...
        foreach ($trickMessage as $message) {
            $text .= $message;
        }

        $text .= VisionText::displayLine();
        return $text;
    }

    /**
     * Mount message to trick.
     * @param int $trick Value of comparation with number rand.
     * @param string $signalTrick Signal of comparation with number rand.
     * @param int $lineLength Line length to terminal.
     * @return string
     */
    public static function mountTrickMessage(int $trick, string $signalTrick, int $lineLength): string
    {
        $msg = sprintf("| The number is %s %d.", $signalTrick, $trick);
        return VisionText::formatLine($msg, $lineLength);
    }

    /**
     * Display debug message.
     * @param int $number Number to discovery.
     * @param int $lives Lives to discovery number.
     * @param int $lineLength Line length to terminal.
     * @return string
     */
    public static function displayDebug(int $number, int $lives, int $lineLength): string
    {
        $text = VisionText::displayLine();
        $msg = sprintf("| Debug: number=%d, lives=%d", $number, $lives);
        $text .= VisionText::formatLine($msg, $lineLength);
        $text .= VisionText::displayLine();
        return $text;
    }

    /**
     * Display message to end game.
     * @param string $textEndGame Message to end game.
     * @param int $number Number to discovery.
     * @param int $lineLength Line length to terminal.
     * @return string
     */
    public static function displayGameOver(string $textEndGame, int $number, int $lineLength): string
    {
        $text = VisionText::displayLine();
        $text .= VisionText::formatLine("| Game Over.", $lineLength);
        $text .= VisionText::displayLine();
        $text .= VisionText::formatLine(sprintf("| %s The number is %d.", $textEndGame, $number), $lineLength);
        $text .= VisionText::displayLine();
        return $text;
    }
}
