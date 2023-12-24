# Terminal Game: What's the number?
*A simple PHP game using terminal.*

## Objective:
Find out the number before end of lives.

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

### Hard Core
- Range: **1** and **50**.
- Lives: **1**.

# Getting Started
## Install by Docker:
- `docker-compose up --build -d`
- `docker exec -it terminal-game-app composer install` (Optional: `--no-dev`)
- `docker exec -it terminal-game-app composer dump-autoload --optimize`
- Or `docker start terminal-game-app`

## Commands
- **Default** mode: `php app/main.php`
- **Easy** mode: `php app/main.php --mode easy`
- **Normal** mode: `php app/main.php --mode normal`
- **Hard** mode: `app/main.php --mode hard`
- **Hard Code** mode: `php app/main.php --mode hard-core`
- **Debug** mode: `php app/main.php --debug`

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
## Game Over
```
+-------------------------------------------------+
| Game Over.                                      |
+-------------------------------------------------+
| ='( Oh no! The number is 26.                    |
+-------------------------------------------------+
OR
+-------------------------------------------------+
| Game Over.                                      |
+-------------------------------------------------+
| =) Congratulations! The number is 26.           |
+-------------------------------------------------+
```
# Dev Context

## [PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer)
By Docker
- `docker exec -it terminal-game-app ./vendor/bin/phpcs --colors --report=full --ignore=*/vendor/* --warning-severity=8 --error-severity=1 --standard=PSR12 .`

In container
- `./vendor/bin/phpcs --colors --report=full --ignore=*/vendor/* --warning-severity=8 --error-severity=1 --standard=PSR12 .`

**[PSR-12](https://www.php-fig.org/psr/psr-12/)*

## [PHP Unit](https://phpunit.de/documentation.html)
By docker
- All: `docker exec -it terminal-game-app ./vendor/bin/phpunit --verbose tests`
- One: `docker exec -it terminal-game-app ./vendor/bin/phpunit --filter testDisplayHeader tests/VisionTextTest.php`

In container
- All: `./vendor/bin/phpunit --verbose tests`
- One: `./vendor/bin/phpunit --filter testDisplayHeader tests/VisionTextTest.php`
