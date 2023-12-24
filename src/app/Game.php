<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Yaml\Yaml;

/**
 * Class Game, representation of game.
 * @package App
 */
final class Game
{
    /**
     * Mode easy.
     */
    public const MODE_EASY = 'easy';

    /**
     * Mode normal.
     */
    public const MODE_NORMAL = 'normal';

    /**
     * Mode hard.
     */
    public const MODE_HARD = 'hard';

    /**
     * Mode hard-core.
     */
    public const MODE_HARD_CORE = 'hard-core';

    /**
     * Mode of game.
     *
     * @var string
     */
    private $mode = '';

    /**
     * Line length, terminal
     *
     * @var integer
     */
    private $lineLength = 0;

    /**
     * Debug to developer =).
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
     * Trick messages.
     *
     * @var array
     */
    private $trickMessage = [];

    /**
     * Number random.
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
     * Command input.
     *
     * @var integer
     */
    private $command = null;

    /**
     * (x_x) Game over .
     *
     * @var boolean
     */
    private $gameOver = false;

    /**
     * Constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);
    }

    /**
     * Set configuration of game.
     * @param array $configInput
     * @return void
     */
    private function setConfig(array $configInput): void
    {
        if ($configInput['debug']) {
            $this->debug = true;
        }

        $config = Yaml::parseFile(__DIR__ . '/config/app.yml');
        $this->lineLength = $config['app']['line-length'];

        $modeInput = isset($configInput['mode']) ? $configInput['mode'] : self::MODE_EASY;
        $item = $this->setModeConfig($modeInput, $config['mode']);
        if ($this->isValidModeStruct($item) == false) {
            throw new \Exception('mode configuration incomplete.');
        }

        $this->mode = $item['label'];
        $this->lives = $item['lives'];
        $this->min = $item['min'];
        $this->max = $item['max'];

        $this->number = $this->getNumber();
    }

    /**
     * Verify if mode is valid.
     * @param string $mode
     * @return boolean
     */
    public static function isValidMode(string $mode): bool
    {
        if (empty($mode)) {
            return true;
        }

        $list = [
            self::MODE_EASY,
            self::MODE_NORMAL,
            self::MODE_HARD,
            self::MODE_HARD_CORE,
        ];

        return in_array(strtolower($mode), $list);
    }

    /**
     * Verify if mode configuration, struct is valid.
     * @param array $item
     * @return boolean
     */
    private function isValidModeStruct(array $item): bool
    {
        return empty(array_diff(['label', 'lives', 'min', 'max'], array_keys($item)));
    }

    /**
     * Set mode configuration.
     * @param string $mode
     * @param array $config
     * @return array
     */
    private function setModeConfig(string &$mode, array &$config): array
    {
        $item = [];
        $index = strtolower($mode);
        if (! isset($config[$index]) && is_array($config[$index])) {
            throw new \Exception('mode configuration not found.');
        }

        return $config[$index];
    }

    /**
     * Get number trick, different of number rand.
     * @return int
     */
    protected function getTrick(): int
    {
        $trick = $this->number;
        while ($trick == $this->number) {
            $trick = $this->getNumber();
        }

        return $trick;
    }

    /**
     * Get signal trick.
     * @param int $target
     * @return string
     */
    private function getSignalTrick(int $target): string
    {
        return $this->number > $target ? '>' : '<';
    }

    /**
     * Get random target number.
     * @return int
     */
    private function getNumber(): int
    {
        return rand($this->min, $this->max);
    }

    /**
     * Play the Game!
     * @return void
     */
    public function run(): void
    {
        $trick = $this->getTrick();
        while (true) {
            VisionText::clear();
            echo VisionText::displayHeader($this->mode, $this->min, $this->max, $this->lives, $this->lineLength);
            $this->trickMessage[] = VisionText::mountTrickMessage($trick, $this->getSignalTrick($trick), $this->lineLength);
            echo VisionText::displayTrick($this->trickMessage, $this->lineLength);

            if ($this->debug) {
                echo VisionText::displayDebug($this->number, $this->lives, $this->lineLength);
            }

            if ($this->gameOver) {
                echo VisionText::displayGameOver($this->getTextEndGame(), $this->number, $this->lineLength);
                break;
            }

            $this->command = readline();
            $this->filterCommand();
            $trick = $this->command;
            // @todo: add command Q for quit game.
            $this->gameOver = $this->isContinueGame();
        }
    }

    /**
     * Get text to end game.
     * @return string
     */
    private function getTextEndGame(): string
    {
        $action = '=\'( Oh no!';
        if ($this->lives > 0) {
            $action = '=) Congratulations!';
        }

        return $action;
    }

    /**
     * Filter command, convert to integer.
     * @return void
     */
    private function filterCommand(): void
    {
        $this->command = (int) $this->command;
    }

    /**
     * Verify if game continue or game over.
     * @return bool
     */
    private function isContinueGame(): bool
    {
        if ($this->number == $this->command) {
            return true;
        }

        $this->lives--;
        return $this->lives === 0;
    }
}
