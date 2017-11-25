<?php

namespace BBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Picture
 *
 * @ORM\Table(name="picture")
 * @ORM\Entity(repositoryClass="BBBundle\Repository\PictureRepository")
 */
class Picture
{
    /**
     * @ORM\ManyToOne(targetEntity="BBBundle\Entity\Draw", inversedBy="picture")
     * @ORM\JoinColumn(name="id_draw", referencedColumnName="id", onDelete="CASCADE")
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
     * @ORM\Column(name="dPath", type="string", length=255, nullable=true)
     */
    private $dPath;


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
     * Set dPath
     *
     * @param string $dPath
     *
     * @return Picture
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
     * Set draw
     *
     * @param \BBBundle\Entity\Draw $draw
     *
     * @return Picture
     */
    public function setDraw(\BBBundle\Entity\Draw $draw)
    {
        $this->draw = $draw;

        return $this;
    }

    /**
     * Get draw
     *
     * @return \BBBundle\Entity\Draw
     */
    public function getDraw()
    {
        return $this->draw;
    }
}
