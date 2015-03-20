<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Role;

class ProfileRoleVoter extends AbstractSupportedRoleVoter
{
    const PROFILE = 'profile';
    const PROFILE_EDIT = 'profile_edit';
    const PROFILE_VIEW = 'profile_view';
    const HOME = 'home';
    /**
     * @inheritdoc
     */
    protected function setSupportedAttributes()
    {
        $this->supportedAttributes = [self::PROFILE, self::PROFILE_EDIT, self::PROFILE_VIEW, self::HOME];

        return $this;
    }
    /**
     * @inheritdoc
     */
    protected function getSupportedRole()
    {
        return Role::OPERATOR;
    }
}
