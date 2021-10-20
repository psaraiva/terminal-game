<?php

declare(strict_types=1);

namespace App;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Log extends LogLevel implements LoggerInterface
{
    private const PREFIX_LINE_PATTER = '%s [%s] ';

    private const LOG_FILE = __DIR__ . '/../log/app.log';

    private function getPrefixLine($level): string
    {
        return sprintf(self::PREFIX_LINE_PATTER, self::getCurrentDateTime(), strtoupper($level));
    }

    private static function getCurrentDateTime(): string
    {
        $d = new \DateTime();
        return $d->format('Y-m-d H:i:s');
    }

    public function emergency($message, array $context = [])
    {
        return $this->log(self::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = [])
    {
        return $this->log(self::ALERT, $message, $context);
    }

    public function critical($message, array $context = [])
    {
        return $this->log(self::CRITICAL, $message, $context);
    }

    public function error($message, array $context = [])
    {
        return $this->log(self::ERROR, $message, $context);
    }

    public function warning($message, array $context = [])
    {
        return $this->log(self::WARNING, $message, $context);
    }

    public function notice($message, array $context = [])
    {
        return $this->log(self::NOTICE, $message, $context);
    }

    public function info($message, array $context = [])
    {
        return $this->log(self::INFO, $message, $context);
    }

    public function debug($message, array $context = [])
    {
        return $this->log(self::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = [])
    {
        $context = implode(PHP_EOL, $context);
        $line = $this->getPrefixLine($level) . $message . $context . PHP_EOL;

        $this->output($line);
    }

    private function output(string $line):  void
    {
        $this->outputFile($line);
    }

    private function outputFile($line): void
    {
        file_put_contents(self::LOG_FILE, $line, FILE_APPEND);
    }
}
