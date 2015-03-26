<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class ProjectsRoleVoter extends AbstractSupportedRoleVoter
{
    const PROJECTS = 'projects';
    const PROJECTS_LIST =  'projects_list';
    const PROJECTS_ADD =  'projects_add';
    const PROJECTS_EDIT = 'projects_edit';
    const PROJECTS_MEMBERS_LIST = 'projects_members_list';
    const PROJECTS_MEMBERS_ADD = 'projects_members_add';
    const PROJECTS_MEMBERS_DELETE = 'projects_members_remove';

    /**
     * @inheritdoc
     */
    protected function setSupportedAttributes()
    {
        $this->supportedAttributes = [
            self::PROJECTS,
            self::PROJECTS_LIST,
            self::PROJECTS_ADD,
            self::PROJECTS_EDIT,
            self::PROJECTS_MEMBERS_LIST,
            self::PROJECTS_MEMBERS_ADD,
            self::PROJECTS_MEMBERS_DELETE
        ];

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
            /** @var User $user */
            $user = $token->getUser();
            if ($this->isManagerAttributeAndNotManager($user, $attribute)) {
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

    /**
     * @param User $user
     * @param $attribute
     * @return bool
     */
    private function isManagerAttributeAndNotManager(User $user, $attribute)
    {
        return in_array(
            $attribute,
            [
                self::PROJECTS_ADD,
                self::PROJECTS_EDIT,
                self::PROJECTS_MEMBERS_LIST,
                self::PROJECTS_MEMBERS_ADD,
                self::PROJECTS_MEMBERS_DELETE
            ]
        )
        && !$this->hasRole($user, Role::MANAGER);
    }
}
