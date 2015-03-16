<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Role;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ProjectsRoleVoter extends AbstractSupportedRoleVoter
{
    const PROJECTS = 'projects';
    const PROJECTS_LIST =  'projects_list';
    const PROJECTS_ADD =  'projects_add';

    /**
     * @inheritdoc
     */
    protected function setSupportedAttributes()
    {
        $this->supportedAttributes = [self::PROJECTS, self::PROJECTS_LIST, self::PROJECTS_ADD];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $vote = parent::vote($token, $object, $attributes);
        if ($vote === VoterInterface::ACCESS_GRANTED) {
            $this->checkAttributes($attributes);
            $attribute = $attributes[0];
            if ($attribute === self::PROJECTS_ADD &&
                !$this->hasRole($token->getUser(), Role::MANAGER)
            ) {
                return VoterInterface::ACCESS_DENIED;
            }
        }
        return $vote;
    }

    /**
     * @inheritdoc
     */
    protected function getSupportedRole()
    {
        return Role::OPERATOR;
    }
}
