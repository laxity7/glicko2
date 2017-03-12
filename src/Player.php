<?php

namespace Pelmered\Glicko2;

final class Player
{
    const CONVERT = 173.7178;

    const DEFAULT_R = 1500;
    const DEFAULT_RD = 350;
    const DEFAULT_SIGMA = 0.06;

    /**
     * A rating r
     *
     * @var float
     */
    private $rating;

    /** A rating μ
     *
     * @var float
     */
    private $ratingMu;

    /**
     * A rating deviation RD
     *
     * @var float
     */
    private $ratingDeviation;

    /**
     * A rating deviation φ
     *
     * @var float
     */
    private $ratingDeviationPhi;

    /**
     * A rating volatility σ
     *
     * @var float
     */
    private $ratingVolatility;

    /**
     * @param float $rating
     * @param float $RD
     * @param float $sigma
     */
    public function __construct($r = self::DEFAULT_R, $RD = self::DEFAULT_RD, $sigma = self::DEFAULT_SIGMA)
    {
        $this->setRating($r);
        $this->setRatingDeviation($RD);
        $this->setSigma($sigma);
    }

    /**
     * @param float $r
     */
    private function setRating($r)
    {
        $this->rating = $r;
        $this->ratingMu = ($this->rating - self::DEFAULT_R) / self::CONVERT;
    }

    /**
     * @param float $mu
     */
    private function setRatingMu($mu)
    {
        $this->ratingMu = $mu;
        $this->rating = $this->ratingMu * self::CONVERT + self::DEFAULT_R;
    }

    /**
     * @param float $RD
     */
    private function setRatingDeviation($RD)
    {
        $this->ratingDeviation = $RD;
        $this->ratingDeviationPhi = $this->ratingDeviation / self::CONVERT;
    }

    /**
     * @param float $phi
     */
    private function setRatingDeviationPhi($phi)
    {
        $this->ratingDeviationPhi = $phi;
        $this->ratingDeviation = $this->ratingDeviationPhi * self::CONVERT;
    }

    /**
     * @param float $sigma
     */
    private function setSigma($sigma)
    {
        $this->ratingVolatility = $sigma;
    }

    /**
     * @return float
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return float
     */
    public function getRatingMu()
    {
        return $this->ratingMu;
    }

    /**
     * @return float
     */
    public function getRatingDeviation()
    {
        return $this->ratingDeviation;
    }

    /**
     * @return float
     */
    public function getRatingDeviationPhi()
    {
        return $this->ratingDeviationPhi;
    }

    /**
     * @return float
     */
    public function getRatingVolatility()
    {
        return $this->ratingVolatility;
    }

    /**
     * @param CalculationResult $calculationResult
     */
    public function setCalculationResult(CalculationResult $calculationResult)
    {
        $this->setRatingMu($calculationResult->getMu());
        $this->setRatingDeviationPhi($calculationResult->getPhi());
        $this->setSigma($calculationResult->getSigma());
        $this->save();
    }
}
