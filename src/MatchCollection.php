<?php

namespace laxity7\glicko2;

use ArrayIterator;
use ArrayObject;

class MatchCollection extends BaseMatch
{
    /**
     * @var MatchGame[]
     */
    private ArrayObject $matches;

    public function __construct()
    {
        $this->matches = new ArrayObject();
    }

    /**
     * @param MatchGame $match
     */
    public function addMatch(MatchGame $match): void
    {
        $this->matches->append($match);
    }

    /**
     * @return ArrayIterator|MatchGame[]
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
