<?php

declare(strict_types=1);

namespace App;

use ReflectionParameter;

final class Command
{
    public const PARAM_DEBUG = '--debug';
    public const PARAM_MODE = '--mode';

    public static function applyArgs(array $params): array
    {
        $args = [];
        array_shift($params);
        foreach (static::getConstantsParams() as $commandParams) {
            if (! in_array($commandParams, $params)) {
                continue;
            }

            $index = array_search($commandParams, $params);
            $method = static::getMethodByParam($commandParams);
            if (! method_exists(__CLASS__, $method)) {
                continue;
            }

            if ($commandParams == static::PARAM_MODE && ! array_key_exists(++$index, $params)) {
                continue;
            }

            $cast = null;
            if ($commandParams == static::PARAM_MODE) {
                $cast = static::getTypeOfParamByFunction($method);
                $value = static::cast($cast, $params[$index]);
                $args = array_merge($args, static::$method($value));
                continue;
            }

            $args = array_merge($args, static::$method(true));
        }

        return $args;
    }

    private function getTypeOfParamByFunction(string $function): string
    {
        $f = new ReflectionParameter(array(__CLASS__, $function), 0);
        $export = ReflectionParameter::export(
            [
                $f->getDeclaringClass()->name,
                $f->getDeclaringFunction()->name,
            ],
            $f->name,
            true
        );

        return preg_replace('/.*?(\w+)\s+\$' . $f->name . '.*/', '\\1', $export);
    }

    private static function getConstantsParams(): array
    {
        $class = new \ReflectionClass(__CLASS__);
        $list = $class->getConstants();
        foreach ($list as $key => $value) {
            if (preg_match("/^PARAM_*/", $key)) {
                continue;
            }

            unset($list);
        }

        return $list;
    }

    private static function getMethodByParam(string $param): string
    {
        $config = [
            static::PARAM_DEBUG => 'filterDebug',
            static::PARAM_MODE => 'filterMode',
        ];

        if (! array_key_exists(strtolower($param), $config)) {
            return '';
        }

        return $config[$param];
    }

    protected function cast($cast, $value)
    {
        if ($cast == 'bool') {
            return boolval($value);
        }

        if ($cast == 'string') {
            return strval($value);
        }

        return null;
    }

    public static function filterDebug(): array
    {
        return ['debug' => true];
    }

    public static function filterMode(string $mode): array
    {
        $mode = strtolower($mode);
        if (! Game::isValidMode($mode)) {
            return [];
        }

        return ['mode' => $mode];
    }
}
