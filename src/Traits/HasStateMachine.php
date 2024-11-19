<?php

namespace SimonRoland\StateMachine\Traits;

use SimonRoland\StateMachine\Exceptions\InvalidStateTransitionException;

trait HasStateMachine
{
    abstract protected function getStateAttributeName(): string;

    abstract protected function getAllowedTransitions(): array;

    protected function getState(): mixed
    {
        return $this->{$this->getStateAttributeName()};
    }

    protected function getNormalizeState(): mixed
    {
        return $this->getState() instanceof \BackedEnum ? $this->getState()->value : $this->getState();
    }

    public function canTransitionTo(mixed $newState): bool
    {
        $allowedTransitions = $this->getAllowedTransitions();

        if (!array_key_exists($this->getNormalizeState(), $allowedTransitions)) {
            return false;
        }

        return in_array($newState, $allowedTransitions[$this->getNormalizeState()], true);
    }

    public function transitionTo(mixed $newState): void
    {
        if (!$this->canTransitionTo($newState)) {
            throw new InvalidStateTransitionException(
                $this->getState()->name,
                $newState->name
            );
        }

        $this->{$this->getStateAttributeName()} = $newState;
        $this->save();
    }
}
