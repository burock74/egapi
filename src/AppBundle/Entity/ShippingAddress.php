<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ShippingAddress
 *
 * @ORM\Table(name="shipping_addresses")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShippingAddressRepository")
 */
class ShippingAddress
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
     * @ORM\Column(name="country", type="string", length=100)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="zipcode", type="string", length=10)
     */
    private $zipcode;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var boolean
     *
     * @ORM\Column(name="defaultflag", type="boolean")
     */
    private $defaultflag;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="shippingaddress")
     * @ORM\JoinColumn(name="client_id" , referencedColumnName="id", onDelete="CASCADE")
     */
    private $client;


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
     * Set country
     *
     * @param string $country
     *
     * @return ShippingAddress
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return ShippingAddress
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set zipcode
     *
     * @param string $zipcode
     *
     * @return ShippingAddress
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * Get zipcode
     *
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return ShippingAddress
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return ShippingAddress
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;

    }



    /**
     * Set defaultflag
     *
     * @param boolean $flg
     *
     * @return ShippingAddress
     */
    public function setDefaultFlag($defaultflag)
    {
        $this->defaultflag = $defaultflag;

        return $this;
    }

    /**
     * Get defaultflag
     *
     * @return boolean
     */
    public function getDefaultFlag()
    {
        return $this->defaultflag;
    }
}
