<?php

namespace laxity7\glicko2\Test;

use laxity7\glicko2\MatchCollection;
use laxity7\glicko2\MatchGame;
use laxity7\glicko2\Player;
use PHPUnit\Framework\TestCase;

final class Glicko2Test extends TestCase
{

    public function testDefaultPlayer(): void
    {
        $player = new Player();

        self::assertEquals(Player::DEFAULT_RATING, $player->getRating());
        self::assertEquals(Player::DEFAULT_RATING_DEVIATION, $player->getRatingDeviation());
        self::assertEquals(Player::DEFAULT_RATING_VOLATILITY, $player->getRatingVolatility());
    }

    public function testCustomPlayer(): void
    {
        $r = 1700;
        $rd = 300;
        $sigma = 0.04;

        $player = new Player($r, $rd, $sigma);

        self::assertEquals($r, $player->getRating());
        self::assertEquals($rd, $player->getRatingDeviation());
        self::assertEquals($sigma, $player->getRatingVolatility());
    }

    public function testCalculateMatch(): void
    {
        $player1 = new Player(1500, 200, 0.06);
        $player2 = new Player(1400, 30, 0.06);

        $match = new MatchGame($player1, $player2, 1, 0);
        $match->calculate();

        self::assertEquals(1563.564, $this->round($player1->getRating()));
        self::assertEquals(175.403, $this->round($player1->getRatingDeviation()));
        self::assertEquals(0.06, $this->round($player1->getRatingVolatility()));

        self::assertEquals(1398.144, $this->round($player2->getRating()));
        self::assertEquals(31.67, $this->round($player2->getRatingDeviation()));
        self::assertEquals(0.06, $this->round($player2->getRatingVolatility()));
    }

    public function testCalculateMatchCollection(): void
    {
        $player1 = new Player(1500, 200, 0.06);
        $player2 = new Player(1400, 30, 0.06);

        $player3 = clone $player1;
        $player4 = clone $player2;

        $match = new MatchGame($player1, $player2, 1, 0);
        $match->calculate();
        $match = new MatchGame($player1, $player2, 1, 0);
        $match->calculate();

        $matchCollection = new MatchCollection();
        $matchCollection->addMatch(new MatchGame($player3, $player4, 1, 0));
        $matchCollection->addMatch(new MatchGame($player3, $player4, 1, 0));
        $matchCollection->calculate();

        self::assertEquals($this->round($player1->getRating()), $this->round($player3->getRating()));
        self::assertEquals($this->round($player2->getRating()), $this->round($player4->getRating()));
        self::assertEquals($this->round($player1->getRatingDeviation()), $this->round($player3->getRatingDeviation()));
        self::assertEquals($this->round($player2->getRatingDeviation()), $this->round($player4->getRatingDeviation()));
        self::assertEquals(
            $this->round($player1->getRatingVolatility()),
            $this->round($player3->getRatingVolatility())
        );
        self::assertEquals(
            $this->round($player2->getRatingVolatility()),
            $this->round($player4->getRatingVolatility())
        );
    }

    /**
     * For different platforms compatibility
     */
    private function round(float $value): float
    {
        return round($value, 3);
    }
}
