<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Preference
 *
 * @ORM\Table(name="preference")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PreferenceRepository")
 */
class Preference
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotNull()
     * @Assert\Choice(choices = {"art", "architecture", "history", "science-fiction", "sport"}, message = "Choose a valid preference name.")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     * @Assert\NotNull()
     * @Assert\Type("numeric")
     * @Assert\GreaterThan(0)
     * @Assert\LessThanOrEqual(10)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="preferences")
     * @var User
     */
    protected $user;
    
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
     * @return Preference
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
     * Set value
     *
     * @param integer $value
     *
     * @return Preference
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Preference
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function match(Theme $theme)
    {
        return $this->name === $theme->getName();
    }
}
