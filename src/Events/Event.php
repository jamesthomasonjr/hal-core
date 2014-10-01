<?php
# src/QL/Hal/Core/Events/Event.php

namespace QL\Hal\Core\Events;

/**
 *  Event Interface
 *
 *  @author Matt Colf <matthewcolf@quickenloans.com>
 */
interface Event
{
    /**
     *  Get the name of the event
     *
     *  @return string
     */
    public function name();
}
