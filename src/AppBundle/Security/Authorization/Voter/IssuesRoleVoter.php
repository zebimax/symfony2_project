<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Role;

class IssuesRoleVoter extends AbstractSupportedRoleVoter
{
    const ISSUES      = 'issues';
    const ISSUES_LIST = 'issues_list';
    const ISSUES_ADD  = 'issues_add';

    /**
     * @inheritdoc
     */
    protected function setSupportedAttributes()
    {
        $this->supportedAttributes = [self::ISSUES, self::ISSUES_LIST, self::ISSUES_ADD];

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
