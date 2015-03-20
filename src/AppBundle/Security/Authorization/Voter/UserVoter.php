<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Role;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends AbstractRoleVoter
{
    const EDIT = 'edit';
    const VIEW = 'view';

    /**
     * @inheritdoc
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::EDIT,
            self::VIEW
        ));
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\User';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @inheritdoc
     */
    public function vote(TokenInterface $token, $userObject, array $attributes)
    {
        if (!$this->supportsClass(get_class($userObject))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for VIEW, EDIT'
            );
        }

        $attribute = $attributes[0];

        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        if ($user === $userObject || $this->hasRole($user, Role::ADMINISTRATOR)) {
            return VoterInterface::ACCESS_GRANTED;
        }
        if ($this->hasRole($user, Role::OPERATOR) && $attribute == self::VIEW) {
            return VoterInterface::ACCESS_GRANTED;
        }
        return VoterInterface::ACCESS_DENIED;
    }
}
