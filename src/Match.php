<?php

namespace laxity7\glicko2;

class Match extends BaseMatch
{
    private const RESULT_WIN = 1;
    private const RESULT_DRAW = 0.5;
    private const RESULT_LOSS = 0;

    /** @var Player */
    private $player1;
    /** @var Player */
    private $player2;
    /** @var float */
    private $score1;
    /** @var float */
    private $score2;

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
        $this->score1 = (float)$score1;
        $this->score2 = (float)$score2;
    }

    /**
     * @return float
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

        return (float)$matchScore;
    }

    /**
     * @return Player
     */
    public function getPlayer1(): Player
    {
        return $this->player1;
    }

    /**
     * @return Player
     */
    public function getPlayer2(): Player
    {
        return $this->player2;
    }

    /**
     * @param Match $match
     */
    public function calculate(): void
    {
        $player1 = $this->getPlayer1();
        $player2 = $this->getPlayer2();
        $score = $this->getScore();

        $ratingSystem = $this->getRatingSystem();

        $calculationResult1 = $ratingSystem->calculatePlayer($player1, $player2, $score);
        $calculationResult2 = $ratingSystem->calculatePlayer($player2, $player1, (1 - $score));

        $player1->setCalculationResult($calculationResult1);
        $player2->setCalculationResult($calculationResult2);
    }
}
