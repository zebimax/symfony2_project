<?php

namespace AppBundle\EventListener\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface EventDispatcherAwareInterface
{
    /**
     * @param EventDispatcherInterface $dispatcher
     *
     * @return $this
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher);

    /** @return EventDispatcherInterface */
    public function getEventDispatcher();
}
