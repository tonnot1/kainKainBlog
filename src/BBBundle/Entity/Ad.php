<?php

namespace BBBundle\Entity;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Serializable;

/**
 * Ad
 *
 * @ORM\Table(name="ad")
 * @ORM\Entity(repositoryClass="BBBundle\Repository\AdRepository")
 */
class Ad extends BaseUser
{


    public function __construct()
    {
        parent::__construct();

    }


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
     * @ORM\Column(name="ad_one", type="string", length=255, nullable=true)
     */
    private $adOne;

    /**
     * @var string
     *
     * @ORM\Column(name="ad_two", type="string", length=255, nullable=true)
     */
    private $adTwo;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set adOne
     *
     * @param string $adOne
     *
     * @return Ad
     */
    public function setAdOne($adOne)
    {
        $this->adOne = $adOne;

        return $this;
    }

    /**
     * Get adOne
     *
     * @return string
     */
    public function getAdOne()
    {
        return $this->adOne;
    }

    /**
     * Set adTwo
     *
     * @param string $adTwo
     *
     * @return Ad
     */
    public function setAdTwo($adTwo)
    {
        $this->adTwo = $adTwo;

        return $this;
    }

    /**
     * Get adTwo
     *
     * @return string
     */
    public function getAdTwo()
    {
        return $this->adTwo;
    }


    public function getRoles()
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);

//    return $this->roles;
    }


    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        // Ici nous n'avons rien à effacer.
        // Cela aurait été le cas si nous avions un mot de passe en clair.
    }


    /**
     * Set username
     *
     * @param string $username
     *
     * @return Ad
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return Ad
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Ad
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return Ad
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->adOne,
            $this->adTwo,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->adOne,
            $this->adTwo,
            ) = unserialize($serialized);
    }

}
