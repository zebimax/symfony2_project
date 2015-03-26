<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

abstract class AbstractSupportedRoleVoter extends AbstractRoleVoter
{
    /** @var array */
    protected $supportedAttributes = [];

    /**
     * @param RoleHierarchyInterface $roleHierarchyInterface
     */
    public function __construct(RoleHierarchyInterface $roleHierarchyInterface)
    {
        $this->setSupportedAttributes();
        parent::__construct($roleHierarchyInterface);
    }

    /**
     * @inheritdoc
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $this->checkAttributes($attributes);
        $attribute = $attributes[0];

        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return VoterInterface::ACCESS_DENIED;
        }
        if ($this->hasRole($user, $this->getSupportedRole())) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }

    /**
     * @inheritdoc
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, $this->supportedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        return true;
    }

    /**
     * @param array $attributes
     */
    protected function checkAttributes(array $attributes)
    {
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed'
            );
        }
    }

    /**
     * @return $this
     */
    abstract protected function setSupportedAttributes();

    /**
     * @return string
     */
    abstract protected function getSupportedRole();
}
