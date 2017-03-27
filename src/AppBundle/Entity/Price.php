<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use AppBundle\Validator\PriceTypeUnique;

/**
 * Price
 *
 * @ORM\Table(name="price", uniqueConstraints={@ORM\UniqueConstraint(name="prices_type_place_unique", columns={"type", "place_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PriceRepository")
 */
class Price
{
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
     * @ORM\Column(name="type", type="string", length=255)
     * @Assert\NotNull()
     * @Assert\Choice(choices = {"less_than_12", "for_all"}, message = "Choose a valid type.")
     * @PriceTypeUnique
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     * @Assert\NotNull()
     * @Assert\Type("numeric")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $value;
    
    /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="prices")
     * @var Place
     */
    protected $place;

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
     * Set type
     *
     * @param string $type
     *
     * @return Price
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set value
     *
     * @param float $value
     *
     * @return Price
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }




    /**
     * Set place
     *
     * @param \AppBundle\Entity\Place $place
     *
     * @return Price
     */
    public function setPlace(\AppBundle\Entity\Place $place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return \AppBundle\Entity\Place
     */
    public function getPlace()
    {
        return $this->place;
    }
}
