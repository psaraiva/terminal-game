# Terminal Game: What's the number?
*A simple PHP game using terminal.*

-- -
## Objective:
Find out the number before end of lives.

-- -
# Getting Started
## Install by Docker:
- `docker-compose up --build -d`
- `docker exec -it terminal-game-app composer install` (Optional: `--no-dev`)
- `docker exec -it terminal-game-app composer dump-autoload --optimize`

-- -
## Modes
### Easy
- Range: **1** and **20**.
- Lives: **5**.

### Normal
- Range: **1** and **30**.
- Lives: **3**.

### Hard
- Range: **1** and **35**.
- Lives: **2**.

### Hard Code
- Range: **1** and **50**.
- Lives: **1**.

-- -
## Commands
- **Default** mode: `php app/main.php`
- **Easy** mode: `php app/main.php --mode easy`
- **Normal** mode: `php app/main.php --mode normal`
- **Hard** mode: `app/main.php --mode hard`
- **Hard Code** mode: `php app/main.php --mode hard-code`
- **Debug** mode: `php app/main.php --debug`

-- -
### Play by Docker:
- **Default** mode: `docker exec -it terminal-game-app <command_default>`
- **Easy/Normal/Hard/Hard Code/Debug** mode: `docker exec -it terminal-game-app <command_*>`
# Example Output
## Header
```
+-------------------------------------------------+
| Game - Find out the number. [mode:normal]       |
| The number have range 1 - 30.                   |
| You have 3 chance for find out the number       |
+-------------------------------------------------+
```
## Tricks
```
+-------------------------------------------------+
| Trick.                                          |
+-------------------------------------------------+
| The number is < 29.                             |
| The number is > 20.                             |
+-------------------------------------------------+
```
## Game Over (happy)
```
+-------------------------------------------------+
| Game Over.                                      |
+-------------------------------------------------+
| =) Congratulations! The number is 26.           |
+-------------------------------------------------+
```

## Game Over (sad)
```
+-------------------------------------------------+
| Game Over.                                      |
+-------------------------------------------------+
| ='( Oh no! The number is 26.                    |
+-------------------------------------------------+
```

## Debug Mode
```
+-------------------------------------------------+
| Game - Discovery the number. [mode:easy]        |
| The number have range 1 - 20.                   |
| You have 5 chance for discovery the number.     |
+-------------------------------------------------+
| Trick.                                          |
+-------------------------------------------------+
| The number is > 10.                             |
+-------------------------------------------------+
+-------------------------------------------------+
| Debug.                                          |
+-------------------------------------------------+
| Number: 19.                                     |
| lives: 5.                                       |
+-------------------------------------------------+
```

-- -
# Highlights

## [PHP Log](https://github.com/php-fig/log)

Output: **log/app.log**

Tail - output the last part of files:
- `docker exec -it terminal-game-app tail -f -n1 ./log/app.log`

Get rows: DEBUG:
- `docker exec -it terminal-game-app grep DEBUG ./log/app.log`
```log
2021-10-20 11:19:40 [DEBUG] Start the Game.
2021-10-20 11:19:42 [DEBUG] Input -> '10'
2021-10-20 11:19:49 [DEBUG] Input -> '20'
2021-10-20 11:19:52 [DEBUG] Input -> '15'
2021-10-20 11:19:56 [DEBUG] Input -> '12'
2021-10-20 11:20:04 [DEBUG] Input -> '14'
```

Get rows: INFO:
- `docker exec -it terminal-game-app grep INFO ./log/app.log`
```log
2021-10-20 11:19:40 [INFO] +-------------------------------------------------+
2021-10-20 11:19:40 [INFO] | Game - Discovery the number. [mode:easy]        |
2021-10-20 11:19:40 [INFO] | The number have range 1 - 20.                   |
2021-10-20 11:19:40 [INFO] | You have %d chance for discovery the number.    |
2021-10-20 11:19:40 [INFO] +-------------------------------------------------+
2021-10-20 11:19:40 [INFO] | Trick.                                          |
2021-10-20 11:19:40 [INFO] +-------------------------------------------------+
2021-10-20 11:19:40 [INFO] | The number is > 7.                              |
2021-10-20 11:19:40 [INFO] +-------------------------------------------------+
2021-10-20 11:19:42 [INFO] +-------------------------------------------------+
2021-10-20 11:19:42 [INFO] | Trick.                                          |
2021-10-20 11:19:42 [INFO] +-------------------------------------------------+
2021-10-20 11:19:42 [INFO] | The number is > 10.                             |
2021-10-20 11:19:42 [INFO] +-------------------------------------------------+
2021-10-20 11:19:49 [INFO] +-------------------------------------------------+
2021-10-20 11:19:49 [INFO] | Trick.                                          |
2021-10-20 11:19:49 [INFO] +-------------------------------------------------+
2021-10-20 11:19:49 [INFO] | The number is < 20.                             |
2021-10-20 11:19:49 [INFO] +-------------------------------------------------+
2021-10-20 11:19:52 [INFO] +-------------------------------------------------+
2021-10-20 11:19:52 [INFO] | Trick.                                          |
2021-10-20 11:19:52 [INFO] +-------------------------------------------------+
2021-10-20 11:19:52 [INFO] | The number is < 15.                             |
2021-10-20 11:19:52 [INFO] +-------------------------------------------------+
2021-10-20 11:19:56 [INFO] +-------------------------------------------------+
2021-10-20 11:19:56 [INFO] | Trick.                                          |
2021-10-20 11:19:56 [INFO] +-------------------------------------------------+
2021-10-20 11:19:56 [INFO] | The number is > 12.                             |
2021-10-20 11:19:56 [INFO] +-------------------------------------------------+
2021-10-20 11:20:04 [INFO] +-------------------------------------------------+
2021-10-20 11:20:04 [INFO] | Game Over.                                      |
2021-10-20 11:20:04 [INFO] +-------------------------------------------------+
2021-10-20 11:20:04 [INFO] | ='( Oh no! The number is 13.                    |
2021-10-20 11:20:04 [INFO] +-------------------------------------------------+
```

**[PSR-3: Logger Interface](https://www.php-fig.org/psr/psr-3/)*

## [Composer](https://getcomposer.org/)

A Dependency Manager for PHP

**[PSR-4: Improved Autoloading](https://www.php-fig.org/psr/psr-4/)*

-- -
# Dev Context

## [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
Example of usage
- `docker exec -it terminal-game-app php ./vendor/bin/phpcs --colors --report=full --ignore=*/vendor/* --warning-severity=8 --error-severity=1 --extensions=php --standard=PSR12 .`

**[PSR-12: Extended Coding Style](https://www.php-fig.org/psr/psr-12/)*

-- -
## [PHP Psalm](https://psalm.dev/)
Example of usage
- `docker exec -it terminal-game-app ./vendor/bin/psalm  --show-info=true`

-- -
## [PHP Insights](https://phpinsights.com/)

Execute command by docker:

- `docker exec -it terminal-game-app ./vendor/bin/phpinsights` (default)
- `docker exec -it terminal-game-app ./vendor/bin/phpinsights analyse app/Http/Controllers/DiceController.php` (by file)
- `docker exec -it terminal-game-app ./vendor/bin/phpinsights analyse app/Http/Controllers/` (by folder)
- `docker exec -it terminal-game-app ./vendor/bin/phpinsights analyse --format=json > DiceClass.json app/Models/Dice.php` (save json)

-- -
## [PHP Pest](https://pestphp.com/)
Execute command by docker:

- `docker exec -it terminal-game-app ./vendor/bin/pest` (default)
- `docker exec -it terminal-game-app ./vendor/bin/pest --group first-play` (by group)
- `docker exec -it terminal-game-app ./vendor/bin/pest --coverage` (by coverage)

Groups
- first-play

*_Check config in `phpunit.xml`. e `Pest.php`._

**Tests will be written soon...*
