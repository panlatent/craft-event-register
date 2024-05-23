<?php

namespace panlatent\craft\event\register;

use Closure;
use Craft;
use craft\events\DefineBehaviorsEvent;
use craft\events\RegisterComponentTypesEvent;
use ReflectionClass;
use yii\base\Component;
use yii\base\Event;
use yii\base\InvalidConfigException;

class Register extends Component
{
    public string $path = '@config/events.php';

    public ?object $configObject = null;

    /**
     * @var Closure[]
     */
    public array $handlers = [];

    protected array $bootstraps = [];

    protected array $handlerConfigs = [];

    public function init(): void
    {
        $path = Craft::getAlias($this->path);
        if (is_file($path)) {
            $config = require $path;
            if (is_object($config)) {
                $this->configObject = $config;
            } elseif (is_string($config) || (is_array($config) && (isset($config['class']) || isset($config['__class']))) || is_callable($config)) {
                $this->configObject = Craft::createObject($config);
            } elseif (is_array($config)) {
                $this->handlers = $config;
            } else {
                throw new InvalidConfigException();
            }
        }

        if ($this->configObject !== null) {
            foreach ((new ReflectionClass($this->configObject))->getMethods(\ReflectionMethod::IS_PUBLIC) as $refMethod) {
                $this->resolveFunction(new ReflectionMethod($refMethod, $this->configObject));
            }
        }

        foreach ($this->handlers as $function) {
            $this->resolveFunction(new \ReflectionFunction($function));
        }
    }

    public function apply($app): void
    {
        foreach ($this->bootstraps as $bootstrap) {
            $bootstrap($app);
        }
    }

    public function registerHandlers(): void
    {
        foreach ($this->handlerConfigs as [$class, $event, $handler]) {
            Event::on($class, $event, $handler);
        }
    }

    protected function resolveFunction(\ReflectionFunction|ReflectionMethod $fn): void
    {
        foreach ($fn->getAttributes() as $attribute) {
            switch ($attribute->getName()) {
                case Bootstrap::class:
                    $this->bootstraps[] = $fn->getClosure();
                    break;
                case RegisterEvent::class:
                    $instance = $attribute->newInstance();
                    $this->handlerConfigs[] = [$instance->class, $instance->event, $fn->getClosure()];
                    break;
                case RegisterComponentTypes::class:
                    $instance = $attribute->newInstance();
                    $this->handlerConfigs[] = [$instance->class, $instance->event, fn(RegisterComponentTypesEvent $e) => $e->types = $instance->replace ? $fn->invoke() : array_merge($e->types, $fn->invoke())];
                    break;
                case DefineBehaviors::class:
                    $instance = $attribute->newInstance();
                    if ($instance->class === '') {
                        $refParams = $fn->getParameters();
                        if (!isset($refParams[0])) {
                            throw new \Exception('DefineBehaviors method ' . $fn->getName() . ' must have 1 parameter when class is null');
                        }
                        $instance->class = $refParams[0]->getType()->getName();
                    }
                    $this->handlerConfigs[] = [$instance->class, $instance->event, fn(DefineBehaviorsEvent $e) => $e->behaviors = $instance->replace ? $fn->invoke($e->sender) : array_merge($e->behaviors, $fn->invoke($e->sender))];
                    break;
            }
        }
    }
}