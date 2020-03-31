<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';

/**
 * Class MappIntelligenceCustomer
 */
class MappIntelligenceCustomer extends MappIntelligenceBasic
{
    protected $id = '';
    protected $email = '';
    protected $emailRID = '';
    protected $emailOptin = false;
    protected $firstName = '';
    protected $lastName = '';
    protected $telephone = '';
    protected $gender = 0;
    protected $birthday = '';
    protected $country = '';
    protected $city = '';
    protected $postalCode = '';
    protected $street = '';
    protected $streetNumber = '';
    protected $validation = false;
    protected $category = array();

    /**
     * MappIntelligenceCustomer constructor.
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
    }

    /**
     * @return array
     */
    protected function getQueryList()
    {
        return array(
            'id' => 'cd',
            'email' => 'uc700',
            'emailRID' => 'uc701',
            'emailOptin' => 'uc702',
            'firstName' => 'uc703',
            'lastName' => 'uc704',
            'telephone' => 'uc705',
            'gender' => 'uc706',
            'birthday' => 'uc707',
            'country' => 'uc708',
            'city' => 'uc709',
            'postalCode' => 'uc710',
            'street' => 'uc711',
            'streetNumber' => 'uc712',
            'validation' => 'uc713',
            'category' => 'uc'
        );
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $emailRID
     *
     * @return $this
     */
    public function setEmailRID($emailRID)
    {
        $this->emailRID = $emailRID;

        return $this;
    }

    /**
     * @param bool $emailOptin
     *
     * @return $this
     */
    public function setEmailOptin($emailOptin)
    {
        $this->emailOptin = $emailOptin;

        return $this;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @param string $telephone
     *
     * @return $this
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @param int $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @param string $birthday
     *
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param string $postalCode
     *
     * @return $this
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @param string $street
     *
     * @return $this
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @param string $streetNumber
     *
     * @return $this
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * @param bool $validation
     *
     * @return $this
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * @param int $id
     * @param string $value
     *
     * @return $this
     */
    public function setCategory($id, $value)
    {
        $this->category[$id] = $value;

        return $this;
    }
}
