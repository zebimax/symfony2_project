<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Role;

class UsersRoleVoter extends AbstractSupportedRoleVoter
{
    const USERS      = 'users';
    const USERS_LIST = 'users_list';
    const USERS_ADD  = 'users_add';

    /**
     * @inheritdoc
     */
    protected function setSupportedAttributes()
    {
        $this->supportedAttributes = [self::USERS, self::USERS_LIST, self::USERS_ADD];

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function getSupportedRole()
    {
        return Role::ADMINISTRATOR;
    }
}
