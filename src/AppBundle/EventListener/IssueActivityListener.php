<?php

namespace AppBundle\EventListener;

use AppBundle\EventListener\Event\IssueActivityEvent;
use AppBundle\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IssueActivityListener implements EventSubscriberInterface
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
            IssueActivityEvent::ISSUE_ACTIVITY => 'onAppIssueActivity',
        ];
    }

    /**
     * @param IssueActivityEvent $event
     */
    public function onAppIssueActivity(IssueActivityEvent $event)
    {
        $this->mailService->sendIssueActivityMail($event->getActivity());
    }
}
