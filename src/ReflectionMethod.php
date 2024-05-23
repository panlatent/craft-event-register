<?php

namespace panlatent\craft\event\register;

class ReflectionMethod extends \ReflectionFunctionAbstract
{
    public function __construct(private \ReflectionMethod $method, private $object) {}

    public static function export() {}

    public function __toString()
    {
        return $this->method->__toString();
    }

    public function invoke(...$args)
    {
        return $this->method->invoke($this->object, ...$args);
    }

    public function getClosure(): \Closure
    {
        return $this->method->getClosure($this->object);
    }

    public function __call($name, $args)
    {
        return $this->method->$name(...$args);
    }

    public function getAttributes(?string $name = null, int $flags = 0): array
    {
        return $this->method->getAttributes($name, $flags);
    }

    public function getParameters(): array
    {
        return $this->method->getParameters();
    }
}