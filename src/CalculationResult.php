<?php

namespace laxity7\glicko2;

final class CalculationResult
{
    /**
     * @var float
     */
    private $mu;

    /**
     * @var float
     */
    private $phi;

    /**
     * @var float
     */
    private $sigma;

    /**
     * @param float $mu
     * @param float $phi
     * @param float $sigma
     */
    public function __construct(float $mu, float $phi, float $sigma)
    {
        $this->mu = $mu;
        $this->phi = $phi;
        $this->sigma = $sigma;
    }

    /**
     * @return float
     */
    public function getMu(): float
    {
        return $this->mu;
    }

    /**
     * @return float
     */
    public function getPhi(): float
    {
        return $this->phi;
    }

    /**
     * @return float
     */
    public function getSigma(): float
    {
        return $this->sigma;
    }
}
