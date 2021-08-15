<?php

namespace App;

/**
 * Class Number, representation of game.
 *
 * @author Pedro Saraiva
 */
final class Game
{

    /**
     * Line of separation to print.
     *
     * @var string
     */
    public const LINE = "+-------------------------------------------------+";

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

    private $mode = '';

    /**
     * Length of line - terminal.
     *
     * @var integer
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
     * @var integer
     */
    private $lives = 0;

    /**
     * Help message to player.
     *
     * @var array
     */
    private $trickMessage = array();

    /**
     * Number selected (random).
     *
     * @var integer
     */
    private $number = 0;

    /**
     * To rand - value minimum.
     *
     * @var integer
     */
    private $min = 0;

    /**
     * To rand - value maximum.
     *
     * @var integer
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
     * @var boolean
     */
    private $gameOver = false;

    /**
     * Construction of class.
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);
    }

    public static function isValidMode(string $mode): bool
    {
        $modes = [
            static::MODE_EASY,
            static::MODE_NORMAL,
            static::MODE_HARD,
            static::MODE_HARD_CODE,
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
            $config['mode'] = static::MODE_NORMAL;
        }

        $this->mode = $config['mode'];
        $this->setModeConfig($config['mode']);

        $this->number = $this->getNumber();
    }

    // @todo refactory
    private function setModeConfig($mode): void
    {
        $config = [];
        if (strtolower($mode) == static::MODE_EASY) {
            $config = static::CONFIG_EASY;
        }

        if (strtolower($mode) == static::MODE_NORMAL) {
            $config = static::CONFIG_NORMAL;
        }

        if (strtolower($mode) == static::MODE_HARD) {
            $config = static::CONFIG_HARD;
        }

        if (strtolower($mode) == static::MODE_HARD_CODE) {
            $config = static::CONFIG_HARD_CORE;
        }

        $this->setConfigMode($config);
    }

    private function setConfigMode(array $config): void
    {
        if (! static::isValidConfigMode($config)) {
            $this->mode = static::MODE_NORMAL;
            $config = static::CONFIG_HARD;
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
        if (! count($intersect) == count($expected)) {
            return false;
        }

        return true;
    }

    /**
     * Main function run the game.
     *
     * @return void.
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
     * @return int
     */
    private function getNumber(): int
    {
        return rand($this->min, $this->max);
    }

    /**
     * Display line
     *
     * @return string
     */
    private function displayLine(): string
    {
        return self::LINE . PHP_EOL;
    }

    /**
     * Display header of game.
     * @return string
     */
    private function displayHeader(): string
    {
        $text = $this->displayLine();
        $text .= $this->formatLine(sprintf("| Game - Discovery the number. [mode:%s]", $this->mode)) ;
        $text .= $this->formatLine(sprintf("| The number have range %d - %d.", $this->min, $this->max)) ;
        $text .= $this->formatLine(sprintf("| You have %d chance for discovery the number.", $this->lives)) ;
        return $text;
    }

    /**
     * Display first help message for player.
     * The first message is especial, use rand for seed.
     *
     * @return string
     */
    private function displayFirstTrick(): string
    {
        $trick = $this->number;
        while ($trick == $this->number) {
            $trick = $this->getNumber();
        }

        return $this->displayTrick($trick);
    }

    /**
     * Filter the command inserted by player, parse to int.
     */
    private function filterCommand(): void
    {
        $this->command = (int) $this->command;
    }

    /**
     * Is logic body the game, verify if game is over.
     * @return boolean
     */
    private function continueGame(): bool
    {
        if ($this->number == $this->command) {
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
     * @return string
     */
    private function formatLine($text): string
    {
        $text = str_pad($text, $this->lineLength);
        $text = sprintf("%s|%s", $text, PHP_EOL);
        return $text;
    }

    /**
     * Format help message to player.
     * @param string $trick Value of comparation with number rand.
     * @return string
     */
    private function displayTrick(string $trick): string
    {
        $keyword = '<';
        if ($this->number > $trick) {
            $keyword = '>';
        }

        $this->trickMessage[] = $this->formatLine(sprintf("| The number is %s %d.", $keyword, $trick));

        $text  = $this->displayLine();
        $text .= $this->formatLine('| Trick.') ;
        $text .= $this->displayLine();

        foreach ($this->trickMessage as $message) {
            $text .= $message;
        }

        $text .= $this->displayLine();
        return $text;
    }

    /**
     * Display end game with message of goodbye.
     * @return string
     */
    private function displayGameOver(): string
    {
        $action = '=\'( Oh no!';
        if ($this->lives > 0) {
            $action = '=) Congratulations!';
        }

        system('clear');
        $text = $this->displayLine();
        $text .= $this->formatLine('| Game Over.') ;
        $text .= $this->displayLine();
        $text .= $this->formatLine(sprintf("| %s The number is %d.", $action, $this->number)) ;
        $text .= $this->displayLine();

        return $text;
    }

    /**
     * Display debug status for developer.
     * @return string
     */
    private function displayDebug(): string
    {
        $text = $this->displayLine();
        $text .= $this->formatLine('| Debug.') ;
        $text .= $this->displayLine();
        $text .= $this->formatLine(sprintf("| Number: %d.", $this->number)) ;
        $text .= $this->formatLine(sprintf("| lives: %d.", $this->lives)) ;
        $text .= $this->displayLine();

        return $text;
    }
}
