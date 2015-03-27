<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordService
{
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
     * @param ComputerPasswordGenerator $passwordGenerator
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
     * @param $plainPassword
     * @param int $length
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
    }
}
