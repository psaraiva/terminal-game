<?php
/**
 * Class Game, representation of game.
 *
 * @author Pedro Saraiva
 */
class Game
{

    /**
     * Line of separation to print.
     *
     * @var string
     */
    const LINE = "+-------------------------------------------------+";

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
    private $live = 0;

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
    public function Game()
    {
        $this->config();
    }

    /**
     * Configuration the game.
     */
    private function config()
    {
        $this->debug = false;
        $this->lineLength = 50;
        $this->live = 3;
        $this->min = 1;
        $this->max = 50;
        $this->number = $this->getNumber();
    }

    /**
     * Main function run the game.
     *
     * @return void.
     */
    public function run()
    {
        system('clear');
        echo $this->displayHeader();
        echo $this->displayFirstTrick();

        while (!$this->gameOver) {
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
    private function getNumber()
    {
        return rand($this->min, $this->max);
    }

    /**
     * Display line
     *
     * @return string
     */
    private function displayLine()
    {
        return self::LINE . "\n";
    }

    /**
     * Display header of game.
     * @return string
     */
    private function displayHeader()
    {
        $text = $this->displayLine();
        $text .= $this->formatLine("| Game - Discovery the number.") ;
        $text .= $this->formatLine(sprintf("| The number have range %d - %d.", $this->min, $this->max)) ;
        $text .= $this->formatLine(sprintf("| You have %d chance for discovery the number.", $this->live)) ;
        return $text;
    }

    /**
     * Display first help message for player.
     * The first message is especial, use rand for seed.
     *
     * @return string
     */
    private function displayFirstTrick()
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
    private function filterCommand()
    {
        $this->command = (int) $this->command;
    }

    /**
     * Is logic body the game, verify if game is over.
     * @return boolean
     */
    private function continueGame()
    {
        if ($this->number == $this->command) {
            return true;
        }

        $this->live--;
        if ($this->live === 0) {
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
    private function formatLine($text)
    {
        $text = str_pad($text, $this->lineLength);
        $text = sprintf("%s|%s", $text, "\n");
        return $text;
    }

    /**
     * Format help message to player.
     * @param string $trick Value of comparation with number rand.
     * @return string
     */
    private function displayTrick($trick)
    {
        $keyword = "<";
        if ($this->number > $trick) {
            $keyword = ">";
        }

        $this->trickMessage[] = $this->formatLine(sprintf("| The number is %s %d.", $keyword, $trick));

        $text  = $this->displayLine();
        $text .= $this->formatLine(sprintf("| Trick.")) ;
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
    private function displayGameOver()
    {
        $action = "='( Oh no!";
        if ($this->live > 0) {
            $action = "=) Congratulations!";
        }

        system('clear');
        $text = $this->displayLine();
        $text .= $this->formatLine("| Game Over.") ;
        $text .= $this->displayLine();
        $text .= $this->formatLine(sprintf("| %s The number is %d.", $action, $this->number)) ;
        $text .= $this->displayLine();
        return $text;
    }

    /**
     * Display debug status for developer.
     * @return string
     */
    private function displayDebug()
    {
        $text = $this->displayLine();
        $text .= $this->formatLine("| Debug.") ;
        $text .= $this->displayLine();
        $text .= $this->formatLine(sprintf("| Number: %d.", $this->number)) ;
        $text .= $this->formatLine(sprintf("| Lives: %d.", $this->live)) ;
        $text .= $this->displayLine();
        return $text;
    }
}

$game = new Game();
$game->run();
