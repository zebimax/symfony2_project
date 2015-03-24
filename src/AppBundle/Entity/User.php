<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * AppBundle\Entity\User
 *
 * @ORM\Table(name="bt_user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\Users")
 * @ORM\HasLifecycleCallbacks
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class User implements UserInterface, \Serializable
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var ArrayCollection Role[]
     *
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="bt_user_to_role")
     */
    private $roles;

    /**
     * @var ArrayCollection Project[]
     *
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="users")
     */
    private $projects;

    /**
     * @var ArrayCollection Issue[]
     *
     * @ORM\ManyToMany(targetEntity="Issue", mappedBy="collaborators")
     */
    private $issues;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var UploadedFile
     */
    protected $file;

    private $temp;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->isActive = true;
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add roles
     *
     * @param Role $roles
     * @return User
     */
    public function addRole(Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * @param Role $roles
     * @return $this
     */
    public function removeRole(Role $roles)
    {
        $this->roles->removeElement($roles);

        return $this;
    }

    /**
     * Get roles
     *
     * @return Role[]
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * Add projects
     *
     * @param Project $projects
     * @return User
     */
    public function addProject(Project $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * @param Project $projects
     * @return $this
     */
    public function removeProject(Project $projects)
    {
        $this->projects->removeElement($projects);

        return $this;
    }

    /**
     * Get projects
     *
     * @return Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add issues
     *
     * @param Issue $issues
     * @return User
     */
    public function addIssue(Issue $issues)
    {
        $this->issues[] = $issues;

        return $this;
    }

    /**
     * @param Issue $issues
     * @return $this
     */
    public function removeIssue(Issue $issues)
    {
        $this->issues->removeElement($issues);

        return $this;
    }

    /**
     * Get issues
     *
     * @return Collection
     */
    public function getIssues()
    {
        return $this->issues;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password
            ]
        );
    }

    /**
     * @param string $serialized
     *
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }

    /**
     * @return null|string
     */
    public function getPrimaryRole()
    {
        $hasRoleAdmin = $hasRoleManager = $hasRoleOperator = false;
        foreach ($this->roles as $role) {
            /** @var Role $role */
            if (Role::ADMINISTRATOR === $role->getRole()) {
                $hasRoleAdmin = true;
                break;
            }
            if (Role::MANAGER === $role->getRole()) {
                $hasRoleManager = true;
                continue;
            }
            if (Role::OPERATOR === $role->getRole()) {
                $hasRoleOperator = true;
            }
        }
        if ($hasRoleAdmin) {
            return Role::ADMINISTRATOR;
        } elseif ($hasRoleManager) {
            return Role::MANAGER;
        } else {
            return $hasRoleOperator ? Role::OPERATOR : null;
        }
    }

    /**
     * @return mixed
     */
    public function getRolesArray()
    {
        return array_reduce(
            $this->getRoles(),
            function ($carry, $item) {
                /** @var Role $item */
                $carry[$item->getRole()] = $item->getId();
                return $carry;
            },
            []
        );
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
            $this->avatar = null;
        } else {
            $this->avatar = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->avatar = $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // check if we have an old image
        if (null !== $this->temp) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->getFile()->guessExtension()
        );

        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (null !== $this->temp) {
            unlink($this->temp);
        }
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->avatar
            ? null
            : $this->getUploadRootDir().'/'.$this->id.'.'.$this->avatar;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->avatar
            ? null
            : $this->getUploadDir().'/'.$this->id.'.'.$this->avatar;
    }

    /**
     * @return bool
     */
    public function isManager()
    {
        return in_array($this->getPrimaryRole(), [Role::MANAGER, Role::ADMINISTRATOR]);
    }

    /**
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    /**
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/avatars';
    }
}
