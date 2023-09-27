<?php

namespace laxity7\glicko2;

class Player
{
    public const DEFAULT_RATING = 1500;
    public const DEFAULT_RATING_DEVIATION = 350;
    public const DEFAULT_RATING_VOLATILITY = 0.06;

    /** @var float Scaling factor */
    private const RATIO = 173.7178;

    /**
     * A rating r
     */
    private float $rating;

    /**
     * A rating μ
     */
    private float $ratingMu;

    /**
     * A rating deviation RD
     */
    private float $ratingDeviation;

    /**
     * A rating deviation φ
     */
    private float $ratingDeviationPhi;

    /**
     * A rating volatility σ
     */
    private float $ratingVolatility;

    /**
     * Player constructor.
     *
     * @param float $rating
     * @param float $ratingDeviation
     * @param float $ratingVolatility
     */
    public function __construct(
        float $rating = self::DEFAULT_RATING,
        float $ratingDeviation = self::DEFAULT_RATING_DEVIATION,
        float $ratingVolatility = self::DEFAULT_RATING_VOLATILITY
    ) {
        $this->setRating($rating);
        $this->setRatingDeviation($ratingDeviation);
        $this->setRatingVolatility($ratingVolatility);
    }

    /**
     * @param float $rating
     */
    private function setRating(float $rating): void
    {
        $this->rating = $rating;
        $this->ratingMu = ($this->rating - static::DEFAULT_RATING) / self::RATIO;
    }

    /**
     * @param float $mu
     */
    private function setRatingMu(float $mu): void
    {
        $this->ratingMu = $mu;
        $this->rating = $this->ratingMu * self::RATIO + static::DEFAULT_RATING;
    }

    /**
     * @param float $ratingDeviation
     */
    private function setRatingDeviation(float $ratingDeviation): void
    {
        $this->ratingDeviation = $ratingDeviation;
        $this->ratingDeviationPhi = $this->ratingDeviation / self::RATIO;
    }

    /**
     * @param float $phi
     */
    private function setRatingDeviationPhi(float $phi): void
    {
        $this->ratingDeviationPhi = $phi;
        $this->ratingDeviation = $this->ratingDeviationPhi * self::RATIO;
    }

    /**
     * @param float $ratingVolatility
     */
    private function setRatingVolatility(float $ratingVolatility): void
    {
        $this->ratingVolatility = $ratingVolatility;
    }

    /**
     * @return float
     */
    public function getRating(): float
    {
        return $this->rating;
    }

    /**
     * @return float
     */
    public function getRatingMu(): float
    {
        return $this->ratingMu;
    }

    /**
     * @return float
     */
    public function getRatingDeviation(): float
    {
        return $this->ratingDeviation;
    }

    /**
     * @return float
     */
    public function getRatingDeviationPhi(): float
    {
        return $this->ratingDeviationPhi;
    }

    /**
     * @return float
     */
    public function getRatingVolatility(): float
    {
        return $this->ratingVolatility;
    }

    /**
     * @param CalculationResult $calculationResult
     * @deprecated
     */
    public function setCalculationResult(CalculationResult $calculationResult): void
    {
        $this->updateRating($calculationResult);
    }

    /**
     * @param CalculationResult $calculationResult
     */
    public function updateRating(CalculationResult $calculationResult): void
    {
        $this->setRatingMu($calculationResult->getMu());
        $this->setRatingDeviationPhi($calculationResult->getPhi());
        $this->setRatingVolatility($calculationResult->getSigma());
    }
}
