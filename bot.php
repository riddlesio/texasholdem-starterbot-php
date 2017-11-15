<?php
/**
 * Texas Hold'em starter bot
 * The TODO's below give you an idea where to start.
 *
 * API: https://docs.riddles.io/texas-hold-em/api
 *
 * Tips:
 * - https://github.com/lox/specialk-php
 * - https://packagist.org/packages/bourdeau/handevaluator-bundle
 * - https://github.com/bourdeau/handevaluator-bundle
 *
 * __main__
 */

$bot = new Bot();
$bot->run();

/**
 * Class Bot
 */
class Bot
{
    private $sb = 30;
    private $bb = 60;
    private $isOnButton = false;
    private $round = 0;
    private $betRound = 'preflop';
    private $playerNames = [];
    private $stack = 0;
    private $botName = '0';
    private $opponentBotName = '1';
    private $debugging = true;

    /**
     * Run the bot
     */
    public function run()
    {
        $stdin = fopen('php://stdin', 'r');

        while ($line = fgets($stdin)) {
            if (strlen($line) == 0) continue;
            $line = preg_replace( "/\r|\n/", "", $line );
            $cmdArgs = explode(" ", $line);
            $cmd = array_shift($cmdArgs);

            switch ($cmd) {
                case 'settings': $this->settingsCommand($cmdArgs); break;
                case 'update': $this->updateCommand($cmdArgs); break;
                case 'action': $this->actionCommand(array_pop($cmdArgs)); break;
                default: $this->log('Untracked command: ' . $line); break;
            }
        }

        fclose($stdin);
    }

    /**
     * Settings
     * @param array $args
     */
    private function settingsCommand($args)
    {
        switch($args[0]) {
            case 'player_names': $this->playerNames = explode(',', $args[1]); break;
            case 'your_bot':
                $this->botName = $args[1];
                $this->opponentBotName = $this->playerNames[0] === $this->botName
                    ? $this->playerNames[1]
                    : $this->playerNames[0];
                break;
            case 'initial_stack': $this->stack = $args[1]; break;
            default: break; // TODO: Untracked settings
        }
    }

    /**
     * Update
     * @param array $args
     */
    private function updateCommand($args)
    {
        switch($args[0]) {
            case 'game':
                switch($args[1]) {
                    case 'round': $this->round = (int)$args[2]; break;
                    case 'small_blind': $this->sb = (int)$args[2]; break;
                    case 'big_blind': $this->bb = (int)$args[2]; break;
                    case 'on_button': $this->isOnButton = $this->botName === $args[2]; break;
                    case 'bet_round': $this->betRound = $args[2]; break;
                    case 'table':
                        // TODO: Community cards
                        break;
                    default: $this->log('Untracked command: game ' . $args[1]); break;
                }
                break;
            case $this->botName:
                // TODO: game update for our bot
                break;
            case $this->opponentBotName:
                // TODO: game update for opponent bot
                break;
        }
    }

    /**
     * Action
     * @param int $timeLimit
     */
    private function actionCommand($timeLimit)
    {
        $move = $this->pickBestMove();
        $this->move($move);
    }

    /**
     * Pick best move
     * @return string
     */
    private function pickBestMove()
    {
        // TODO: Your magic here.
        return ($this->betRound === 'river')
            ? 'raise_' . (2 * $this->bb) // minimal raise
            : $this->getDefaultMove();
    }

    /**
     * Default move
     * @return string
     */
    private function getDefaultMove()
    {
        return 'call';
    }

    /**
     * Print move
     * @param string $move
     */
    private function move($move)
    {
        echo $move . PHP_EOL;
    }

    /**
     * Log
     * @param string $string
     */
    private function log($string)
    {
        if ($this->debugging) error_log($string);
    }
}