<?php

namespace laxity7\glicko2;

abstract class BaseMatch
{
    /** @var Glicko2 */
    private $ratingSystem;

    /**
     * @return Glicko2
     */
    protected function getRatingSystem(): Glicko2
    {
        if ($this->ratingSystem === null) {
            $this->ratingSystem = new Glicko2();
        }

        return $this->ratingSystem;
    }

    /**
     * Calculate match
     */
    abstract public function calculate();
}