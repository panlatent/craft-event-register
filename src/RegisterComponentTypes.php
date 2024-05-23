<?php

namespace panlatent\craft\event\register;

#[\Attribute(\Attribute::TARGET_FUNCTION|\Attribute::TARGET_METHOD)]
class RegisterComponentTypes extends RegisterEvent
{
    public function __construct(public string $class, public string $event, public bool $replace = false)
    {
        parent::__construct($class, $event);
    }
}