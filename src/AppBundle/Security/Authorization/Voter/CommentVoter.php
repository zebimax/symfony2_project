<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class CommentVoter extends AbstractRoleVoter
{
    const EDIT   = 'edit';
    const REMOVE = 'remove';

    /**
     * @inheritdoc
     */
    public function supportsAttribute($attribute)
    {
        return in_array(
            $attribute,
            [
                self::EDIT,
                self::REMOVE,
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\Comment';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @inheritdoc
     */
    public function vote(TokenInterface $token, $comment, array $attributes)
    {
        if (!$this->supportsClass(get_class($comment))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }
        /* @var Comment $comment */
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for EDIT, REMOVE'
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

        if ($user->getId() === $comment->getUser()->getId() || $this->hasRole($user, Role::ADMINISTRATOR)) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
