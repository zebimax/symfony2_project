<?php

namespace AppBundle\EventListener;

use AppBundle\EventListener\Event\CreateUserEvent;
use AppBundle\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CreateUserEventListener implements EventSubscriberInterface
{
    /** @var MailService */
    protected $mailService;

    /**
     * @param MailService $mailService
     */
    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            CreateUserEvent::CREATE_USER_EVENT => 'onAppCreateUserEvent',
        ];
    }

    /**
     * @param CreateUserEvent $event
     */
    public function onAppCreateUserEvent(CreateUserEvent $event)
    {
        $this->mailService->sendCreateUserMail($event->getUser(), $event->getPassword());
    }
}
