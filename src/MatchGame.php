<?php

namespace laxity7\glicko2;

class MatchGame extends BaseMatch
{
    /** The actual score for win */
    private const RESULT_WIN = 1.0;
    /** The actual score for draw */
    private const RESULT_DRAW = 0.5;
    /** The actual score for loss */
    private const RESULT_LOSS = 0.0;

    private Player $player1;
    private Player $player2;
    /** The player1 score */
    private float $score1;
    /** The player2 score */
    private float $score2;

    /**
     * @param Player $player1
     * @param Player $player2
     * @param float $score1
     * @param float $score2
     */
    public function __construct(Player $player1, Player $player2, float $score1, float $score2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
        $this->score1 = $score1;
        $this->score2 = $score2;
    }

    /**
     * Get the 1st player score
     *
     * @return float The 1st player score (0 for a loss, 0.5 for a draw, and 1 for a win)
     */
    public function getScore(): float
    {
        $diff = $this->score1 - $this->score2;
        switch (true) {
            case $diff < 0 :
                $matchScore = self::RESULT_LOSS;
                break;
            case $diff > 0 :
                $matchScore = self::RESULT_WIN;
                break;
            default :
                $matchScore = self::RESULT_DRAW;
                break;
        }

        return $matchScore;
    }

    /**
     * The winner of the match. Null if it's a draw.
     *
     * @return Player|null
     */
    public function getWinner(): ?Player
    {
        switch ($this->getScore()) {
            case self::RESULT_WIN:
                return $this->player1;
            case self::RESULT_LOSS:
                return $this->player2;
            default:
                return null;
        }
    }

    /**
     * Get the 1st player
     *
     * @return Player
     */
    public function getPlayer1(): Player
    {
        return $this->player1;
    }

    /**
     * Get the 2nd player
     *
     * @return Player
     */
    public function getPlayer2(): Player
    {
        return $this->player2;
    }

    /**
     * Calculate and change the rating of players
     */
    public function calculate(): void
    {
        $player1 = $this->getPlayer1();
        $player2 = $this->getPlayer2();
        $score = $this->getScore();

        $ratingSystem = $this->getRatingSystem();

        $calculationResult1 = $ratingSystem->calculatePlayer($player1, $player2, $score);
        $calculationResult2 = $ratingSystem->calculatePlayer($player2, $player1, (1 - $score));

        $player1->updateRating($calculationResult1);
        $player2->updateRating($calculationResult2);
    }
}
