<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Project;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectVoter extends AbstractRoleVoter
{
    const VIEW = 'view';
    const ISSUE_ADD = 'issue_add';

    /**
     * @inheritdoc
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, [self::VIEW, self::ISSUE_ADD]);
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\Project';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @inheritdoc
     */
    public function vote(TokenInterface $token, $project, array $attributes)
    {
        if (!$this->supportsClass(get_class($project))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        /** @var Project $project */
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for VIEW, ISSUE_ADD'
            );
        }

        $attribute = $attributes[0];

        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        /** @var User $user */
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        if ($this->hasRole($user, Role::MANAGER) || $project->isMember($user)) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
