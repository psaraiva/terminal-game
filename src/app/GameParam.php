<?php

declare(strict_types=1);

namespace App;

use Exception;
use Log;
use ReflectionClass;
use ReflectionParameter;

final class GameParam
{
    private const PREFIX_PARAM_PATTERN = '/^--*/';
    private const PREFIX_CONST_PARAM_PATTERN = '/^PARAM_*/';

    public const PARAM_DEBUG = '--debug';
    public const PARAM_MODE = '--mode';

    private $inputParams = [];

    /**
     * @var array
     */
    private $args = [];

    public function __construct()
    {
        $this->inputParams = $this->getConstantsParams();
    }

    /**
     * Apply args
     *
     * @return self
     */
    public function apply(array $params): self
    {
        array_shift($params);
        if (! $this->isValidParamExist($params)) {
            return $this;
        }

        foreach ($params as $param) {
            try {
                $this->checkCommand($param);
                $this->validParam($param);
                $method = $this->getMethodByParam($param);
                $this->validMethod($method);
                $index = array_search($param, $params);
                $this->validIndexForMethod($index);

                if ($param === self::PARAM_MODE) {
                    $this->args = array_merge($this->args, $this->getParamModeValue($method, $params, $index));
                }

                if ($param === self::PARAM_DEBUG) {
                    $this->args = array_merge($this->args, $this->$method());
                }
            } catch (Exception $e) {
                continue;
            }
        }

        return $this;
    }

    private function checkCommand(string $command): void
    {
        if (! preg_match(self::PREFIX_PARAM_PATTERN, $command)) {
            throw new Exception('Command out of pattern');
        }
    }

    private function isValidParamExist(array $params): bool
    {
        return count($params) > 0;
    }

    private function validParam(string $param): void
    {
        if (! in_array($param, $this->inputParams)) {
            throw new Exception('Param invalid!');
        }
    }

    private function validMethod($method): void
    {
        if (! method_exists($this, $method)) {
            throw new Exception('Method invalid!');
        }
    }

    private function validIndexForMethod($index): void
    {
        if ($index === false) {
            throw new Exception('Index for method is invalid!');
        }
    }

    /**
     * Get value to param mode.
     * 
     * @return T
     */
    private function getParamModeValue($method, $params, $index): array
    {
        $index++;
        if (! array_key_exists($index, $params)) {
            throw new Exception('Index for method value not found!');
        }

        $cast = $this->getTypeOfParamByFunction($method);
        $value = $this->cast($cast, $params[$index]);
        return $this->$method($value);
    }

    private function getTypeOfParamByFunction(string $function): string
    {
        $f = new ReflectionParameter([$this, $function], 0);
        $class = $f->getDeclaringClass()->name;
        $function = $f->getDeclaringFunction()->name;
        if (! is_string($class) || ! is_string($function)) {
            throw new Exception('Function not found!');
        }

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

    /**
     * Get params, consts PARAM_*
     * 
     * @return T
     */
    private function getConstantsParams(): array
    {
        $class = new ReflectionClass($this);
        $list = $class->getConstants();
        foreach ($list as $key => $value) {
            if (preg_match(self::PREFIX_CONST_PARAM_PATTERN, $key)) {
                continue;
            }

            unset($list[$key]);
        }

        return $list;
    }

    private function getMethodByParam(string $param): string
    {
        $config = [
            self::PARAM_DEBUG => 'filterDebug',
            self::PARAM_MODE => 'filterMode',
        ];

        if (! array_key_exists(strtolower($param), $config)) {
            throw new Exception('Method not found!');
        }

        return $config[$param];
    }

    private function cast($cast, $value)
    {
        if ($cast === 'bool') {
            return boolval($value);
        }

        if ($cast === 'string') {
            return strval($value);
        }

        return null;
    }

    /**
     * @return T
     */
    public function filterDebug(): array
    {
        return ['debug' => true];
    }

    /**
     * @return T
     */
    public function filterMode(string $mode): array
    {
        $mode = strtolower($mode);
        if (! Game::isValidMode($mode)) {
            return [];
        }

        return ['mode' => $mode];
    }

    public function toArray(): array
    {
        return $this->args;
    }
}
