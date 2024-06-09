<?php

namespace LaunchpadConstants;

use LaunchpadConstants\Sanitizers\ConstantSanitizer;
use LaunchpadDispatcher\Dispatcher;

class Constants implements ConstantsInterface
{

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function has(string $name): bool
    {
        $exists = defined($name);

        return $this->dispatcher->apply_bool_filters("constant_{$name}_exists", $exists);
    }

    public function get(string $name)
    {
        $value = $this->dispatcher->apply_filters("constant_{$name}_value", new ConstantSanitizer(), null);

        if( $value ) {
            return $value;
        }

        $value = constant($name);

        return $this->dispatcher->apply_filters("constant_{$name}_value", new ConstantSanitizer(), $value);
    }

    public function set(string $name, $value)
    {
        if(! defined($name)) {
            return;
        }

        define($name, $value);

        $this->dispatcher->do_action("constant_{$name}_defined", $value);
    }
}