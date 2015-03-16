<?php

namespace AppBundle\Menu\Route;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserIdParameterProvider implements ParameterProviderInterface
{
    private $userId;

    /**
     * @param TokenStorageInterface $storageInterface
     */
    public function __construct(TokenStorageInterface $storageInterface)
    {
        $user = $storageInterface->getToken()->getUser();
        if ($user instanceof User) {
            $this->userId = $user->getId();
        }
    }
    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return ['id' => $this->userId];
    }
}
