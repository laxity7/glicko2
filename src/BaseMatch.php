<?php

namespace laxity7\glicko2;

abstract class BaseMatch
{
    private Glicko2 $ratingSystem;

    /**
     * @return Glicko2
     */
    protected function getRatingSystem(): Glicko2
    {
        $this->ratingSystem ??= new Glicko2();

        return $this->ratingSystem;
    }

    /**
     * Calculate match
     */
    abstract public function calculate(): void;
}
