<?php

namespace Roland\StateMachine\Exceptions;

use Exception;

class InvalidStateTransitionException extends Exception
{
    public function __construct(string $currentState, string $targetState)
    {
        $message = "Invalid transition from '{$currentState}' to '{$targetState}'.";
        parent::__construct($message);
    }
}
