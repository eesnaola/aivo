<?php

namespace AppBundle\Model;

use JMS\Serializer\Annotation as JMS;

class User
{

    /**
     * @var int
     *
     * @JMS\SerializedName("id")
     * @JMS\Type("integer")
     * @JMS\Groups({ "user" })
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\SerializedName("firstName")
     * @JMS\Type("string")
     * @JMS\Groups({ "user" })
     */
    private $firstName;

    /**
     * @var string
     *
     * @JMS\SerializedName("lastName")
     * @JMS\Type("string")
     * @JMS\Groups({ "user" })
     */
    private $lastName;


    /**
     * Get the value of Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     *
     * @param int id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of First Name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of First Name
     *
     * @param string firstName
     *
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of Last Name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of Last Name
     *
     * @param string lastName
     *
     * @return self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

}
