<?php

namespace AppBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractRoleVoter implements VoterInterface
{
    /**
     * @var RoleHierarchyInterface
     */
    protected $roleHierarchy;

    /**
     * @param RoleHierarchyInterface $roleHierarchyInterface
     */
    public function __construct(RoleHierarchyInterface $roleHierarchyInterface)
    {
        $this->roleHierarchy = $roleHierarchyInterface;
    }

    /**
     * @param UserInterface $user
     * @param string $checkRole
     *
     * @return bool
     */
    final protected function hasRole(UserInterface $user, $checkRole)
    {
        $roles = $this->roleHierarchy->getReachableRoles($user->getRoles());

        foreach ($roles as $role) {
            if ($checkRole === $role->getRole()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    abstract public function vote(TokenInterface $token, $object, array $attributes);

    /**
     * @inheritdoc
     */
    abstract public function supportsAttribute($attribute);

    /**
     * @inheritdoc
     */
    abstract public function supportsClass($class);
}
