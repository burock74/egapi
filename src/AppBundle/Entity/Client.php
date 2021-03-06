<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
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
     * @ORM\Column(name="firstname", type="string", length=255)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     */
    private $lastname;


    /**
     * @ORM\OneToMany(targetEntity="ShippingAddress", mappedBy="client")
     */
    private $shippingaddress;

    public function __construct()
    {
        $this->shippingaddress = new ArrayCollection();
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Client
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Client
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Add shippingaddress
     *
     * @param \AppBundle\Entity\ShippingAddress $shippingaddress
     *
     * @return Client
     */
    public function addShippingaddress(\AppBundle\Entity\ShippingAddress $shippingaddress)
    {
        $this->shippingaddress[] = $shippingaddress;
        return $this;
    }

    /**
     * Remove shippingaddress
     *
     * @param \AppBundle\Entity\ShippingAddress $shippingaddress
     */
    public function removeShippingaddress(\AppBundle\Entity\ShippingAddress $shippingaddress)
    {
        $this->shippingaddress->removeElement($shippingaddress);
    }

    /**
     * Get shippingaddress
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShippingaddress()
    {
        return $this->shippingaddress;
    }

    /**
     * Get shippingaddresscount
     *
     * @return integer
     */
    public function getShippingaddressCount()
    {
        return $this->shippingaddress->count();
    }
}