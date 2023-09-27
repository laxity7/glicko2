# Glicko2 rating system

[![License](https://img.shields.io/github/license/laxity7/glicko2.svg)](https://github.com/laxity7/glicko2/blob/master/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/laxity7/glicko2.svg)](https://packagist.org/packages/laxity7/glicko2)
[![Total Downloads](https://img.shields.io/packagist/dt/laxity7/glicko2.svg)](https://packagist.org/packages/laxity7/glicko2)

A PHP implementation of [Glicko2 rating system](http://www.glicko.net/glicko.html)

The Glicko2 rating system is a popular algorithm used to calculate the skill levels of players in various competitive
games. It takes into account both the player's performance and the strength of their opponents, providing more accurate
and up-to-date ratings compared to traditional methods like Elo. The system uses a logarithmic function that adjusts a
player's rating based on the outcome of their matches, with higher-skilled players having a greater impact on their
opponent's ratings. Glicko2 also employs a confidence interval and a smoothing parameter to further refine the accuracy
and stability of the rankings.

The Glicko2 rating system is an advanced Elo system that was used in Mark Zuckerberg's infamous Facemash website

Supports PHP versions 7.4 and 8.*

## Installation

For installation via Composer run:

`composer require laxity7/glicko2`

## Usage

For example, you have a table in the database with the following fields:

```php
use laxity7\glicko2\Player;
use App\UserRating;

class UserRating
{
    public int $user_id;
    public float $rating;
    public float $rating_deviation;
    public float $rating_volatility;
}

class Repository
{
    public function getUserRating(int $userId)
    {
        $userRating = $this->findInDbFromId($userId);
        if (!$userRating) {
            $userRating = $this->createUserRating($userId);
        }
        return $userRating;
    }
    
    public function createUserRating(int $userId)
    {
        $player = new Player();
        $userRating = new UserRating();
        $userRating->user_id = $userId;
        $userRating->rating = $player->getRating();
        $userRating->rating_deviation = $player->getRatingDeviation();
        $userRating->rating_volatility = $player->getRatingVolatility();
    
        $this->saveToDb($userRating);
        
        return $userRating;
    }
    
    public function updateRating(int $userId, Player $player)
    {
        $userRating = $this->getUserRating($userId);
        $userRating->rating = $player->getRating();
        $userRating->rating_deviation = $player->getRatingDeviation();
        $userRating->rating_volatility = $player->getRatingVolatility();

        $this->saveToDb($userRating);
    }
}
```

Ok, preparation completed, lets play

```php
use laxity7\glicko2\MatchGame;
use laxity7\glicko2\MatchCollection;
use laxity7\glicko2\Player;

$repository = new Repository();

$userRating1 = $repository->getUserRating(1);
$userRating2 = $repository->getUserRating(2);

$player1 = new Player($userRating1->rating, $userRating1->rating_deviation, $userRating1->rating_volatility);
$player2 = new Player($userRating2->rating, $userRating2->rating_deviation, $userRating2->rating_volatility);
//$player2 = new Player(); available defaults for new player

// match chain
$match1 = new MatchGame($player1, $player2, 1, 0);
$match1->calculate(); // The calculation method does not return anything, it only calculates and changes the rating of players

$match2 = new MatchGame($player1, $player2, 3, 2);
$match2->calculate();

// or match collection
$matchCollection = new MatchCollection();
$matchCollection->addMatch(new MatchGame($player1, $player2, 1, 0));
$matchCollection->addMatch(new MatchGame($player1, $player2, 3, 2));
$matchCollection->calculate();

// just get a new ratings
$newPlayer1Rating = $player1->getRating();
$newPlayer2Rating = $player2->getRating();

// for example, save to the database
$repository->updateRating(1, $player1);
$repository->updateRating(2, $player2);
```

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)

[Vlad Varlamov](https://github.com/laxity7/), e-mail: [vlad@varlamov.dev](mailto:vlad@varlamov.dev)
