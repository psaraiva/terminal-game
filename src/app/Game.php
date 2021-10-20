<?php

declare(strict_types=1);

namespace App;

/**
 * Class Game, representation of game.
 *
 * @author Pedro Saraiva
 */
final class Game
{
    public const LINE = '+-------------------------------------------------+';

    public const MODE_EASY = 'easy';
    public const MODE_NORMAL = 'normal';
    public const MODE_HARD = 'hard';
    public const MODE_HARD_CODE = 'hard-code';

    private const CONFIG_EASY = [
        'min' => 1,
        'max' => 20,
        'lives' => 5,
    ];

    private const CONFIG_NORMAL = [
        'min' => 1,
        'max' => 30,
        'lives' => 3,
    ];

    private const CONFIG_HARD = [
        'min' => 1,
        'max' => 35,
        'lives' => 2,
    ];

    private const CONFIG_HARD_CORE = [
        'min' => 1,
        'max' => 50,
        'lives' => 1,
    ];

    /**
     * Type of mode game.
     *
     * @var string
     */
    private $mode = '';

    /**
     * Length of line - terminal.
     *
     * @var int
     */
    private $lineLength = 0;

    /**
     * Debug to developer.
     *
     * @var string
     */
    private $debug = false;

    /**
     * Player lives.
     *
     * @var int
     */
    private $lives = 0;

    /**
     * Help message to player.
     *
     * @var T
     */
    private $trickMessage = array();

    /**
     * Number selected (random).
     *
     * @var int
     */
    private $number = 0;

    /**
     * To rand - value minimum.
     *
     * @var int
     */
    private $min = 0;

    /**
     * To rand - value maximum.
     *
     * @var int
     */
    private $max = 0;

    /**
     * Command inserted for player.
     *
     * @var string
     */
    private $command = null;

    /**
     * Flag game over.
     *
     * @var bool
     */
    private $gameOver = false;

    /**
     * Object Log.
     *
     * @var Log
     */
    private $log;

    /**
     * Construction of class.
     */
    public function __construct(array $config, Log $log)
    {
        $this->log = $log;
        $this->log->debug('Start the Game.');
        $this->setConfig($config);
    }

    public static function isValidMode(string $mode): bool
    {
        $modes = [
            self::MODE_EASY,
            self::MODE_NORMAL,
            self::MODE_HARD,
            self::MODE_HARD_CODE,
        ];

        return in_array($mode, $modes);
    }

    /**
     * Configuration the game.
     */
    private function setConfig(array $config): void
    {
        $this->lineLength = 50;
        $this->debug = isset($config['debug']) ?? false;

        if (! isset($config['mode'])) {
            $config['mode'] = self::MODE_NORMAL;
        }

        $this->mode = $config['mode'];
        $this->setModeConfig($config['mode']);

        $this->number = $this->getNumber();
    }

    // @todo refactory
    private function setModeConfig($mode): void
    {
        $config = [];
        if (strtolower($mode) === self::MODE_EASY) {
            $config = self::CONFIG_EASY;
        }

        if (strtolower($mode) === self::MODE_NORMAL) {
            $config = self::CONFIG_NORMAL;
        }

        if (strtolower($mode) === self::MODE_HARD) {
            $config = self::CONFIG_HARD;
        }

        if (strtolower($mode) === self::MODE_HARD_CODE) {
            $config = self::CONFIG_HARD_CORE;
        }

        $this->setConfigMode($config);
    }

    private function setConfigMode(array $config): void
    {
        if (! self::isValidConfigMode($config)) {
            $this->mode = self::MODE_NORMAL;
            $config = self::CONFIG_HARD;
        }

        $this->min = $config['min'];
        $this->max = $config['max'];
        $this->lives = $config['lives'];
    }

    private static function isValidConfigMode($config): bool
    {
        $expected = [
            'min' => 0,
            'max' => 0,
            'lives' => 0,
        ];

        $intersect = array_intersect_key($config, $expected);
        if (! count($intersect) === count($expected)) {
            return false;
        }

        return true;
    }

    /**
     * Main function run the game.
     */
    public function run(): void
    {
        system('clear');
        echo $this->displayHeader();
        echo $this->displayFirstTrick();

        while (! $this->gameOver) {
            if ($this->debug) {
                echo $this->displayDebug();
            }

            $this->command = readline();
            $this->filterCommand();
            $this->gameOver = $this->continueGame();
        }

        echo $this->displayGameOver();
    }

    /**
     * Get number rand, use min and max.
     */
    private function getNumber(): int
    {
        return rand($this->min, $this->max);
    }

    /**
     * Display line
     */
    private function displayLine(): string
    {
        $this->log->info(self::LINE);
        return self::LINE . PHP_EOL;
    }

    /**
     * Display header of game.
     */
    private function displayHeader(): string
    {
        $text = $this->displayLine();
        $text .= $this->formatLine(sprintf('| Game - Discovery the number. [mode:%s]', $this->mode));
        $text .= $this->formatLine(sprintf('| The number have range %d - %d.', $this->min, $this->max));
        $text .= $this->formatLine('| You have %d chance for discovery the number.', $this->lives);

        return $text;
    }

    /**
     * Display first help message for player.
     * The first message is especial, use rand for seed.
     */
    private function displayFirstTrick(): string
    {
        $trick = $this->number;
        while ($trick === $this->number) {
            $trick = $this->getNumber();
        }

        return $this->displayTrick($trick);
    }

    /**
     * Filter the command inserted by player, parse to int.
     */
    private function filterCommand(): void
    {
        $this->log->debug(sprintf("Input -> '%s'", $this->command));
        $this->command = (int) $this->command;
    }

    /**
     * Is logic body the game, verify if game is over.
     */
    private function continueGame(): bool
    {
        if ($this->number === $this->command) {
            return true;
        }

        $this->lives--;
        if ($this->lives === 0) {
            return true;
        }

        system('clear');
        echo $this->displayTrick($this->command);
        return false;
    }

    /**
     * Format line of output to terminal.
     * @param string $text Message to formater line output.
     */
    private function formatLine(string $text): string
    {
        $text = str_pad($text, $this->lineLength);
        $this->log->info(sprintf('%s|', $text));

        return sprintf('%s|%s', $text, PHP_EOL);
    }

    /**
     * Format help message to player.
     * @param string $trick Value of comparation with number rand.
     */
    private function displayTrick(int $trick): string
    {
        $text  = $this->displayLine();
        $text .= $this->formatLine('| Trick.');
        $text .= $this->displayLine();

        $keyword = $this->number > $trick ? '>' : '<';
        $this->trickMessage[] = $this->formatLine(sprintf('| The number is %s %d.', $keyword, $trick));

        foreach ($this->trickMessage as $message) {
            $text .= $message;
        }

        $text .= $this->displayLine();
        return $text;
    }

    /**
     * Display end game with message of goodbye.
     */
    private function displayGameOver(): string
    {
        $action = $this->lives > 0 ? '=) Congratulations!' : '=\'( Oh no!';

        system('clear');
        $text = $this->displayLine();
        $text .= $this->formatLine('| Game Over.') ;
        $text .= $this->displayLine();
        $text .= $this->formatLine(sprintf('| %s The number is %d.', $action, $this->number)) ;
        $text .= $this->displayLine();

        return $text;
    }

    /**
     * Display debug status for developer.
     */
    private function displayDebug(): string
    {
        $text = $this->displayLine();
        $text .= $this->formatLine('| Debug.') ;
        $text .= $this->displayLine();
        $text .= $this->formatLine(sprintf('| Number: %d.', $this->number)) ;
        $text .= $this->formatLine(sprintf('| lives: %d.', $this->lives)) ;
        $text .= $this->displayLine();

        return $text;
    }
}
