<?php

namespace laxity7\glicko2;

final class CalculationResult
{
    /** @var float A rating μ */
    private float $mu;

    /** @var float A rating deviation φ */
    private float $phi;

    /** @var float A rating volatility σ */
    private float $sigma;

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
     * @return float A rating μ
     */
    public function getMu(): float
    {
        return $this->mu;
    }

    /**
     * @return float A rating deviation φ
     */
    public function getPhi(): float
    {
        return $this->phi;
    }

    /**
     * @return float A rating volatility σ
     */
    public function getSigma(): float
    {
        return $this->sigma;
    }
}
