<?php

namespace panlatent\craft\event\register;

#[\Attribute(\Attribute::TARGET_FUNCTION|\Attribute::TARGET_METHOD)]
class RegisterEvent
{
    public function __construct(public string $class, public string $event)
    {

    }
}