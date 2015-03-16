<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends AbstractRoleVoter
{
    const VIEW = 'view';
    const USERS_LIST = 'list';
    const EDIT = 'edit';
    const ADD = 'add';

    /**
     * @inheritdoc
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
            self::EDIT,
            self::ADD,
            self::USERS_LIST
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
                'Only one attribute is allowed for ADD, VIEW, EDIT or LIST'
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

        if ($this->hasRole($user, Role::ADMINISTRATOR)) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if ($user === $userObject && in_array($attribute, [self::VIEW, self::EDIT])) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
