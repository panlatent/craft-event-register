<?php

namespace panlatent\craft\event\register;

use yii\base\BootstrapInterface;

class Extension extends Register implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $this->apply($app);
        $this->registerHandlers();
    }
}