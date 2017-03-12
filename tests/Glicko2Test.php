<?php

namespace Pelmered\Glicko2\Test;

use PHPUnit_Framework_TestCase;
use Pelmered\Glicko2\Glicko2;
use Pelmered\Glicko2\Match;
use Pelmered\Glicko2\MatchCollection;
use Pelmered\Glicko2\Player;

final class Glicko2Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var Glicko2
     */
    private $glicko;

    public function setUp()
    {
        $this->glicko = new Glicko2();
        parent::setUp();
    }

    public function testDefaultPlayer()
    {
        $player = new Player();

        $this->assertEquals(Player::DEFAULT_R, $player->getRating());
        $this->assertEquals(Player::DEFAULT_RD, $player->getRatingDeviation());
        $this->assertEquals(Player::DEFAULT_SIGMA, $player->getRatingVolatility());
    }

    public function testCustomPlayer()
    {
        $r = 1700;
        $rd = 300;
        $sigma = 0.04;

        $player = new Player($r, $rd, $sigma);

        $this->assertEquals($r, $player->getRating());
        $this->assertEquals($rd, $player->getRatingDeviation());
        $this->assertEquals($sigma, $player->getRatingVolatility());
    }

    public function testCalculateMatch()
    {
        $player1 = new Player(1500, 200, 0.06);
        $player2 = new Player(1400, 30, 0.06);

        $match = new Match($player1, $player2, 1, 0);
        $this->glicko->calculateMatch($match);

        $this->assertEquals(1563.564, $this->round($player1->getRating()));
        $this->assertEquals(175.403, $this->round($player1->getRatingDeviation()));
        $this->assertEquals(0.06, $this->round($player1->getRatingVolatility()));

        $this->assertEquals(1398.144, $this->round($player2->getRating()));
        $this->assertEquals(31.67, $this->round($player2->getRatingDeviation()));
        $this->assertEquals(0.06, $this->round($player2->getRatingVolatility()));
    }

    public function testCalculateMatchCollection()
    {
        $player1 = new Player(1500, 200, 0.06);
        $player2 = new Player(1400, 30, 0.06);

        $player3 = clone $player1;
        $player4 = clone $player2;

        $match = new Match($player1, $player2, 1, 0);
        $this->glicko->calculateMatch($match);
        $match = new Match($player1, $player2, 1, 0);
        $this->glicko->calculateMatch($match);

        $matchCollection = new MatchCollection();
        $matchCollection->addMatch(new Match($player3, $player4, 1, 0));
        $matchCollection->addMatch(new Match($player3, $player4, 1, 0));
        $this->glicko->calculateMatches($matchCollection);

        $this->assertEquals($this->round($player1->getRating()), $this->round($player3->getRating()));
        $this->assertEquals($this->round($player2->getRating()), $this->round($player4->getRating()));
        $this->assertEquals($this->round($player1->getRatingDeviation()), $this->round($player3->getRatingDeviation()));
        $this->assertEquals($this->round($player2->getRatingDeviation()), $this->round($player4->getRatingDeviation()));
        $this->assertEquals($this->round($player1->getRatingVolatility()), $this->round($player3->getRatingVolatility()));
        $this->assertEquals($this->round($player2->getRatingVolatility()), $this->round($player4->getRatingVolatility()));
    }

    /**
     * For different platforms compatibility
     *
     * @param float $value
     *
     * @return float
     */
    private function round($value)
    {
        return round($value, 3);
    }
}
