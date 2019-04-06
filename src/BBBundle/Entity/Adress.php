<?php

namespace BBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adress
 *
 * @ORM\Table(name="adress")
 * @ORM\Entity(repositoryClass="BBBundle\Repository\AdressRepository")
 */
class Adress
{

    public function __construct()
    {
        $this->setVisitedAt(new \DateTime());
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable= true)
     */
    private $visited_at;


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
     * Set ip
     *
     * @param string $ip
     *
     * @return Adress
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set visitedAt
     *
     * @param \DateTime $visitedAt
     *
     * @return Adress
     */
    public function setVisitedAt($visitedAt)
    {
        $this->visited_at = $visitedAt;

        return $this;
    }

    /**
     * Get visitedAt
     *
     * @return \DateTime
     */
    public function getVisitedAt()
    {
        return $this->visited_at;
    }
}
