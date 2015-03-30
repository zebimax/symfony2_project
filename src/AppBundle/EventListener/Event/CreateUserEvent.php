<?php

namespace AppBundle\EventListener\Event;

use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class CreateUserEvent extends Event
{
    const CREATE_USER_EVENT = 'app.create_user_event';

    /** @var User */
    protected $user;

    /** @var string */
    protected $password;

    /**
     * @param User $user
     * @param $password
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
