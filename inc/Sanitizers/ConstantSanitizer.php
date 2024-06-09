<?php

namespace LaunchpadConstants\Sanitizers;

use LaunchpadDispatcher\Interfaces\SanitizerInterface;

class ConstantSanitizer implements SanitizerInterface
{

    protected $invalid;

    public function sanitize($value)
    {
        $this->invalid = ! is_scalar($value) && ! is_null($value) && ! is_array($value);

        return $value;
    }

    public function is_default($value, $original): bool
    {
        return $this->invalid;
    }
}