<?php

namespace BBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Draw
 *
 * @ORM\Table(name="draw")
 * @ORM\Entity(repositoryClass="BBBundle\Repository\DrawRepository")
 */
class Draw
{
    public function __construct()
{
    $this->setCreatedAt(new \DateTime());
    $this->setUpdatedAt(new \DateTime());
}

    /**
     * @ORM\OneToMany(targetEntity="BBBundle\Entity\Picture", mappedBy="draw")
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity="BBBundle\Entity\Category", inversedBy="draw")
     * @ORM\JoinColumn(name="id_cat", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="BBBundle\Entity\Comments", mappedBy="draw")
     */
    private $comments;

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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="dPath", type="string", length=255, nullable=true)
     */
    private $dPath;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $pouces;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * Set title
     *
     * @param string $title
     *
     * @return Draw
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set dPath
     *
     * @param string $dPath
     *
     * @return Draw
     */
    public function setDPath($dPath)
    {
        $this->dPath = $dPath;

        return $this;
    }

    /**
     * Get dPath
     *
     * @return string
     */
    public function getDPath()
    {
        return $this->dPath;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Draw
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Draw
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set category
     *
     * @param \BBBundle\Entity\Category $category
     *
     * @return Draw
     */
    public function setCategory(\BBBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \BBBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add comment
     *
     * @param \BBBundle\Entity\Comments $comment
     *
     * @return Draw
     */
    public function addComment(\BBBundle\Entity\Comments $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \BBBundle\Entity\Comments $comment
     */
    public function removeComment(\BBBundle\Entity\Comments $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Draw
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set picture
     *
     * @param \BBBundle\Entity\Picture $picture
     *
     * @return Draw
     */
    public function setPicture(\BBBundle\Entity\Picture $picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \BBBundle\Entity\Picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Add picture
     *
     * @param \BBBundle\Entity\Picture $picture
     *
     * @return Draw
     */
    public function addPicture(\BBBundle\Entity\Picture $picture)
    {
        $this->picture[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \BBBundle\Entity\Picture $picture
     */
    public function removePicture(\BBBundle\Entity\Picture $picture)
    {
        $this->picture->removeElement($picture);
    }

    /**
     * Set pouces
     *
     * @param integer $pouces
     *
     * @return Draw
     */
    public function setPouces($pouces)
    {
        $this->pouces = $pouces;

        return $this;
    }

    /**
     * Get pouces
     *
     * @return integer
     */
    public function getPouces()
    {
        return $this->pouces;
    }
}
