<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bt_project")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\Projects")
 * @ORM\HasLifecycleCallbacks
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $code;

    /**
     * @var ArrayCollection User[]
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="projects", indexBy="id", cascade={"persist"})
     * @ORM\JoinTable(name="bt_project_to_user")
     */
    private $users;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    public function __construct()
    {
         $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return Project
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add user
     *
     * @param User $user
     * @return Project
     */
    public function addUser(User $user)
    {
        $this->users->set($user->getId(), $user);

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     * @return bool
     */
    public function removeUser(User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function isMember(User $user)
    {
        return $this->users->contains($user);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        $parts = preg_split("/[\s,_-]+/", $this->label);
        $this->code = strtoupper(
            array_reduce(
                $parts,
                function ($carry, $item) {
                    return $carry . $item[0];
                },
                ''
            )
        );
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }
}
