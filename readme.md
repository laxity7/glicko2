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

Create two players with current ratings:

```php
use laxity7\glicko2\Match;
use laxity7\glicko2\MatchCollection;
use laxity7\glicko2\Player;

$player1 = new Player(1700, 250, 0.05);
$player2 = new Player();

$match = new Match($player1, $player2, 1, 0);
$match->calculate();

$match = new Match($player1, $player2, 3, 2);
$match->calculate();

// or

$matchCollection = new MatchCollection();
$matchCollection->addMatch(new Match($player1, $player2, 1, 0));
$matchCollection->addMatch(new Match($player1, $player2, 3, 2));
$matchCollection->calculate();

$newPlayer1Rating = $player1->getRating();
$newPlayer2Rating = $player2->getRating();
```

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)

[Vlad Varlamov](https://github.com/laxity7/), e-mail: [work@laxity.ru](mailto:work@laxity.ru)
