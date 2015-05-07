<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\EventListener\Event\CreateUserEvent;
use AppBundle\EventListener\EventDispatcher\EventDispatcherAwareInterface;
use AppBundle\EventListener\EventDispatcher\EventDispatcherAwareTrait;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordService implements EventDispatcherAwareInterface
{
    use EventDispatcherAwareTrait;

    const DEFAULT_PASSWORD_LENGTH = 10;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * @var ComputerPasswordGenerator
     */
    protected $passwordGenerator;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ComputerPasswordGenerator    $passwordGenerator
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        ComputerPasswordGenerator $passwordGenerator
    ) {
        $this->passwordGenerator = $passwordGenerator;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @param string $plainPassword
     * @param int  $length
     */
    public function setUserPassword(User $user, $plainPassword, $length = self::DEFAULT_PASSWORD_LENGTH)
    {
        $password = $plainPassword;
        if (!$plainPassword) {
            $password = $this->passwordGenerator->setLength($length)
                ->generatePassword();
        }

        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $password)
        );
        $this->dispatcher->dispatch(
            CreateUserEvent::CREATE_USER_EVENT,
            new CreateUserEvent($user, $password)
        );
    }
}
