<?php
# src/QL/Hal/Core/Events/Emitter.php

namespace QL\Hal\Core\Events;

/**
 *  Event Emitter
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 */
class Emitter
{
    private $listeners = [];

    /**
     *  Register an object subscriber
     *
     *  @param Subscriber $subscriber
     */
    public function register(Subscriber $subscriber)
    {
        foreach ($subscriber->subscriptions() as $event => $callable) {
            $this->on($event, $callable);
        }
    }

    /**
     *  Register an event listener
     *
     *  @param string $event
     *  @param callable $callable
     */
    public function on($event, callable $callable)
    {
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }

        $this->listeners[$event][] = $callable;
    }

    /**
     *  Emit an event
     *
     *  @param Event $event
     */
    public function emit(Event $event)
    {
        foreach ($this->listeners($event) as $callable) {
            call_user_func($callable, $event);
        }
    }

    /**
     *  Get an array of all subscribed listeners for an event
     *
     *  @param Event $event
     *  @return array
     */
    protected function listeners(Event $event)
    {
        return (isset($this->listeners[$event->name()])) ? $this->listeners[$event->name()] : array();
    }
}
