<?php

namespace laxity7\glicko2;

/**
 * Every player in the Glicko-2 system has a rating, r, a rating deviation, RD, and a rating
 * volatility σ. The volatility measure indicates the degree of expected fluctuation in a player’s
 * rating. The volatility measure is high when a player has erratic performances (e.g., when
 * the player has had exceptionally strong results after a period of stability), and the volatility
 * measure is low when the player performs at a consistent level. As with the original Glicko
 * system, it is usually informative to summarize a player’s strength in the form of an interval
 * (rather than merely report a rating). One way to do this is to report a 95% confidence
 * interval. The lowest value in the interval is the player’s rating minus twice the RD, and the
 * highest value is the player’s rating plus twice the RD. So, for example, if a player’s rating
 * is 1850 and the RD is 50, the interval would go from 1750 to 1950. We would then say
 * that we’re 95% confident that the player’s actual strength is between 1750 and 1950. When
 * a player has a low RD, the interval would be narrow, so that we would be 95% confident
 * about a player’s strength being in a small interval of values. The volatility measure does not
 * appear in the calculation of this interval.
 */
final class Glicko2
{
    /**
     * The system constant (τ), which constrains the change in volatility over time, needs to be
     * set prior to application of the system. Reasonable choices are between 0.3 and 1.2,
     * though the system should be tested to decide which value results in greatest predictive
     * accuracy. Smaller values of τ prevent the volatility measures from changing by large 1
     * amounts, which in turn prevent enormous changes in ratings based on very improbable
     * results. If the application of Glicko-2 is expected to involve extremely improbable
     * collections of game outcomes, then τ should be set to a small value, even as small as,
     * say, τ = 0.2.
     *
     * @var float
     */
    private $tau;

    /**
     * @param float $tau
     */
    public function __construct(float $tau = 0.5)
    {
        $this->tau = $tau;
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @param float  $score
     *
     * @return CalculationResult
     */
    public function calculatePlayer(Player $player1, Player $player2, float $score): CalculationResult
    {
        $phi = $player1->getRatingDeviationPhi();
        $mu = $player1->getRatingMu();
        $sigma = $player1->getRatingVolatility();

        $phiJ = $player2->getRatingDeviationPhi();
        $muJ = $player2->getRatingMu();

        $v = $this->v($phiJ, $mu, $muJ);
        $delta = $this->delta($phiJ, $mu, $muJ, $score);
        $sigmaP = $this->sigmaP($delta, $sigma, $phi, $phiJ, $mu, $muJ);
        $phiS = $this->phiS($phi, $sigmaP);
        $phiP = $this->phiP($phiS, $v);
        $muP = $this->muP($mu, $muJ, $phiP, $phiJ, $score);

        return new CalculationResult($muP, $phiP, $sigmaP);
    }

    /**
     * @param float $phiJ
     * @param float $mu
     * @param float $muJ
     *
     * @return float
     */
    private function v(float $phiJ, float $mu, float $muJ): float
    {
        $g = $this->g($phiJ);
        $E = $this->E($mu, $muJ, $phiJ);
        return 1 / ($g * $g * $E * (1 - $E));
    }

    /**
     * @param float $phiJ
     *
     * @return float
     */
    private function g(float $phiJ): float
    {
        return 1 / sqrt(1 + 3 * pow($phiJ, 2) / pow(M_PI, 2));
    }

    /**
     * @param float $mu
     * @param float $muJ
     * @param float $phiJ
     *
     * @return float
     */
    private function E(float $mu, float $muJ, float $phiJ): float
    {
        return 1 / (1 + exp(-$this->g($phiJ) * ($mu - $muJ)));
    }

    /**
     * @param float $phiJ
     * @param float $mu
     * @param float $muJ
     * @param float $score
     *
     * @return float
     */
    private function delta(float $phiJ, float $mu, float $muJ, float $score): float
    {
        return $this->v($phiJ, $mu, $muJ) * $this->g($phiJ) * ($score - $this->E($mu, $muJ, $phiJ));
    }

    /**
     * @param float $delta
     * @param float $sigma
     * @param float $phi
     * @param float $phiJ
     * @param float $mu
     * @param float $muJ
     *
     * @return float
     */
    private function sigmaP(float $delta, float $sigma, float $phi, float $phiJ, float $mu, float $muJ): float
    {
        $A = $a = log(pow($sigma, 2));
        $fX = function ($x, $delta, $phi, $v, $a, $tau) {
            return ((exp($x) * (pow($delta, 2) - pow($phi, 2) - $v - exp($x))) /
                    (2 * pow((pow($phi, 2) + $v + exp($x)), 2))) - (($x - $a) / pow($tau, 2));
        };
        $epsilon = 0.000001;
        $v = $this->v($phiJ, $mu, $muJ);
        $tau = $this->tau;

        if (pow($delta, 2) > (pow($phi, 2) + $v)) {
            $B = log(pow($delta, 2) - pow($phi, 2) - $v);
        } else {
            $k = 1;
            while ($fX($a - $k * $tau, $delta, $phi, $v, $a, $tau) < 0) {
                $k++;
            }
            $B = $a - $k * $tau;
        }

        $fA = $fX($A, $delta, $phi, $v, $a, $tau);
        $fB = $fX($B, $delta, $phi, $v, $a, $tau);

        while (abs($B - $A) > $epsilon) {
            $C = $A + $fA * ($A - $B) / ($fB - $fA);
            $fC = $fX($C, $delta, $phi, $v, $a, $tau);
            if (($fC * $fB) < 0) {
                $A = $B;
                $fA = $fB;
            } else {
                $fA = $fA / 2;
            }
            $B = $C;
            $fB = $fC;
        }

        return exp($A / 2);
    }

    /**
     * @param float $phi
     * @param float $sigmaP
     *
     * @return float
     */
    private function phiS(float $phi, float $sigmaP): float
    {
        return sqrt(pow($phi, 2) + pow($sigmaP, 2));
    }

    /**
     * @param float $phiS
     * @param float $v
     *
     * @return float
     */
    private function phiP(float $phiS, float $v): float
    {
        return 1 / sqrt(1 / pow($phiS, 2) + 1 / $v);
    }

    /**
     * @param float $mu
     * @param float $muJ
     * @param float $phiP
     * @param float $phiJ
     * @param float $score
     *
     * @return float
     */
    private function muP(float $mu, float $muJ, float $phiP, float $phiJ, float $score): float
    {
        return $mu + pow($phiP, 2) * $this->g($phiJ) * ($score - $this->E($mu, $muJ, $phiJ));
    }
}
