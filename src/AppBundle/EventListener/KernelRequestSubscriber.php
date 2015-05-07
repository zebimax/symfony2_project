<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\User;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Twig_Extension_Core;

class KernelRequestSubscriber implements EventSubscriberInterface
{
    const EVENT_PRIORITY = 8;

    /** @var \Twig_Environment */
    protected $twig;

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /**
     * @param \Twig_Environment     $twig
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(\Twig_Environment $twig, TokenStorageInterface $tokenStorage)
    {
        $this->twig         = $twig;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', self::EVENT_PRIORITY],
        ];
    }

    /**
     * @param GetResponseEvent $event
     *
     * @throws \Twig_Error_Runtime
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        /* @var User $user */
        $tokenInterface = $this->tokenStorage->getToken();
        if (null !== $tokenInterface) {
            $user = $tokenInterface->getUser();

            if ($user instanceof User) {
                $timezone = $user->getTimezone() !== null ? $user->getTimezone() : 'UTC';
                /** @var Twig_Extension_Core $twigExtensionCore */
                $twigExtensionCore = $this->twig->getExtension('core');
                $twigExtensionCore->setTimezone($timezone);
            }
        }
    }
}
