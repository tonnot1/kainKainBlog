<?php

namespace BBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="BBBundle\Repository\CategoryRepository")
 */
class Category
{

    public function __toString()
    {
        // TODO: Implement __toString() method.
        $this->getName();
    }

    /**
     * @ORM\OneToMany(targetEntity="BBBundle\Entity\Draw", mappedBy="category")
     */
    private $draw;

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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;


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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->draw = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add draw
     *
     * @param \BBBundle\Entity\Draw $draw
     *
     * @return Category
     */
    public function addDraw(\BBBundle\Entity\Draw $draw)
    {
        $this->draw[] = $draw;

        return $this;
    }

    /**
     * Remove draw
     *
     * @param \BBBundle\Entity\Draw $draw
     */
    public function removeDraw(\BBBundle\Entity\Draw $draw)
    {
        $this->draw->removeElement($draw);
    }

    /**
     * Get draw
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDraw()
    {
        return $this->draw;
    }
}
