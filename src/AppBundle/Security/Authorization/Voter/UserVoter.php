<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter implements VoterInterface
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const VIEW_LIST = 'list';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::VIEW_LIST
        ));
    }

    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\User';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @param TokenInterface $token
     * @param null|object $userObject
     * @param array $attributes
     * @return int
     * @var User $userObject
     */
    public function vote(TokenInterface $token, $userObject, array $attributes)
    {
        if (!$this->supportsClass(get_class($userObject))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for VIEW, EDIT or LIST'
            );
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }
        $isAdmin = in_array(Role::ADMINISTRATOR, $user->getRoles());

        switch($attribute) {
            case self::VIEW:
                if ($user === $userObject || $isAdmin) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;

            case self::EDIT:
                if ($user === $userObject || in_array(Role::ADMINISTRATOR, $user->getRoles())) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
            case self::VIEW_LIST:
                if (in_array(Role::ADMINISTRATOR, $user->getRoles())) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
