# Glicko2 rating system

[![License](https://img.shields.io/github/license/laxity7/glicko2.svg)](https://github.com/laxity7/glicko2/blob/master/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/laxity7/glicko2.svg)](https://packagist.org/packages/laxity7/glicko2)
[![Total Downloads](https://img.shields.io/packagist/dt/laxity7/glicko2.svg)](https://packagist.org/packages/laxity7/glicko2)

A PHP implementation of [Glicko2 rating system](http://www.glicko.net/glicko.html)

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require laxity7/glicko2 "~1.0.0"
```

or add

```
"laxity7/glicko2": "~1.0.0"
```

to the require section of your ```composer.json```

## Usage

For ease of understanding take ActiveRecord pattern.

Somewhere create object. Attribute names can be any

```php
use laxity7\glicko2\Player;
// ...
public function createUserRating(int $userId)
{
    $userRating = new UserRating();
    $userRating->user_id = $userId;
    $player = new Player();
    $userRating->rating = $player->getRating();
    $userRating->rating_deviation = $player->getRatingDeviation();
    $userRating->rating_volatility = $player->getRatingVolatility();

    $userRating->insert();
    
    return $userRating;
}
```

Ok, let's play

```php
use laxity7\glicko2\Match;
use laxity7\glicko2\MatchCollection;
use laxity7\glicko2\Player;

$player1 = new Player($userRating1->rating, $userRating1->rating_deviation, $userRating1->rating_volatility);
$player2 = new Player($userRating2->rating, $userRating2->rating_deviation, $userRating2->rating_volatility);

// match chain
$match1 = new Match($player1, $player2, 1, 0);
$match1->calculate();

$match2 = new Match($player1, $player2, 3, 2);
$match2->calculate();

// or match collection
$matchCollection = new MatchCollection();
$matchCollection->addMatch(new Match($player1, $player2, 1, 0));
$matchCollection->addMatch(new Match($player1, $player2, 3, 2));
$matchCollection->calculate();

$newPlayer1Rating = $player1->getRating();
$newPlayer2Rating = $player2->getRating();

// for example, save in DB

$userRating1->rating = $player1->getRating();
$userRating1->rating_deviation = $player1->getRatingDeviation();
$userRating1->rating_volatility = $player1->getRatingVolatility();
$userRating1->update();

// similarly save the second player
```

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)

[Vlad Varlamov](https://github.com/laxity7/), e-mail: [work@laxity.ru](mailto:work@laxity.ru)
