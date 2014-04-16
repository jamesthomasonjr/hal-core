<?php
# src/QL/Hal/Core/Events/Subscriber.php

namespace QL\Hal\Core\Events;

/**
 *  Event subscriber
 *
 *  Allows an object to subscribe to one or more events.
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 */
interface Subscriber
{
    /**
     *  Return an array of events to listen for and callbacks to be run in the following format.
     *
     *  array(
     *      'event1' => callable,
     *      'event2' => callable,
     *      'event2' => callable
     *  )
     *
     *  When registered with the emitter, the passed callable method will be called and an event object will be passed.
     *
     *  @return array
     */
    public function subscriptions();
}
