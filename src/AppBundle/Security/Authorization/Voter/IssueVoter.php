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
    const ADD_SUB_TASK = 'add_sub_task';
    const COMMENTS_LIST = 'comments_list';
    const ADD_COMMENT = 'add_comment';

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
                self::ADD_SUB_TASK,
                self::COMMENTS_LIST,
                self::ADD_COMMENT,
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
        /* @var Issue $issue */
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for VIEW, EDIT, ADD_SUB_TASK, COMMENTS_LIST'
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

        return VoterInterface::ACCESS_DENIED;
    }
}
