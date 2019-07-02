<?php

namespace laxity7\glicko2;

use ArrayIterator;
use ArrayObject;

class MatchCollection extends BaseMatch
{
    /**
     * @var ArrayObject|Match[]
     */
    private $matches;

    public function __construct()
    {
        $this->matches = new ArrayObject();
    }

    /**
     * @param Match $match
     */
    public function addMatch(Match $match): void
    {
        $this->matches->append($match);
    }

    /**
     * @return ArrayIterator|Match[]
     */
    public function getMatches(): ArrayIterator
    {
        return $this->matches->getIterator();
    }

    /** @inheritDoc */
    public function calculate(): void
    {
        foreach ($this->getMatches() as $match) {
            $match->calculate();
        }
    }
}
