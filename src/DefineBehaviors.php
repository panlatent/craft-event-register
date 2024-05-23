<?php

namespace panlatent\craft\event\register;

use craft\base\Model;

#[\Attribute(\Attribute::TARGET_FUNCTION|\Attribute::TARGET_METHOD)]
class DefineBehaviors extends RegisterEvent
{
    public function __construct(public string $class = '', public string $event = Model::EVENT_DEFINE_BEHAVIORS, public bool $replace = false)
    {
        parent::__construct($class, $event);
    }
}