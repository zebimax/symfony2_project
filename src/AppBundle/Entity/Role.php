<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * @ORM\Table(name="bt_role")
 * @ORM\Entity
 */
class Role implements RoleInterface
{
    const OPERATOR      = 'ROLE_OPERATOR';
    const MANAGER       = 'ROLE_MANAGER';
    const ADMINISTRATOR = 'ROLE_ADMINISTRATOR';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(name="role", type="string", length=64, unique=true)
     */
    protected $role;

    /**
     * @var ArrayCollection User[]
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     */
    protected $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set role.
     *
     * @param string $role
     *
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get users.
     *
     * @return ArrayCollection User[]
     */
    public function getUsers()
    {
        return $this->users;
    }
}
