<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class IssueVoter extends AbstractRoleVoter
{
    const EDIT = 'edit';
    const VIEW = 'view';

    /**
     * @inheritdoc
     */
    public function supportsAttribute($attribute)
    {
        return in_array(
            $attribute,
            [
                self::EDIT,
                self::VIEW,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\Issue';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @inheritdoc
     */
    public function vote(TokenInterface $token, $issue, array $attributes)
    {
        if (!$this->supportsClass(get_class($issue))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        /** @var Issue $issue */
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

        if (!$user instanceof User) {
            return VoterInterface::ACCESS_DENIED;
        }

        if ($issue->getProject()->isMember($user) || $this->hasRole($user, Role::MANAGER)) {
            return VoterInterface::ACCESS_GRANTED;
        }
        if ($this->hasRole($user, Role::OPERATOR) && $attribute == self::VIEW) {
            return VoterInterface::ACCESS_GRANTED;
        }
        return VoterInterface::ACCESS_DENIED;
    }
}