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
    const DEFAULT_CODE = 'project_';
    const CODE_LENGTH  = 5;
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
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

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct()
    {
        $this->users   = new ArrayCollection();
        $this->created = new \DateTime('now', new \DateTimeZone('UTC'));
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
     * Set label.
     *
     * @param string $label
     *
     * @return Project
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add user.
     *
     * @param User $user
     *
     * @return Project
     */
    public function addUser(User $user)
    {
        $this->users->set($user->getId(), $user);

        return $this;
    }

    /**
     * Remove user.
     *
     * @param User $user
     *
     * @return bool
     */
    public function removeUser(User $user)
    {
        return $this->users->removeElement($user);
    }

    /**
     * Get users.
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
     */
    public function prePersist()
    {
        $parts      = preg_split('/[\W]+/', $this->label, self::CODE_LENGTH, PREG_SPLIT_NO_EMPTY);
        $code       = strtoupper(
            array_reduce(
                $parts,
                function ($carry, $item) {
                    return $carry . $item[0];
                },
                ''
            )
        );
        $this->code = !empty($code) ? $code : self::DEFAULT_CODE;

        $this->updated = new \DateTime('now', new \DateTimeZone('UTC'));
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

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     *
     * @return $this
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }
}
