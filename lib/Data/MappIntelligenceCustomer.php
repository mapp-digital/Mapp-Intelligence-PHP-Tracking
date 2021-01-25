<?php

require_once __DIR__ . '/MappIntelligenceBasic.php';
require_once __DIR__ . '/../MappIntelligenceParameter.php';

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
            'id' => MappIntelligenceParameter::$CUSTOMER_ID,
            'email' => MappIntelligenceParameter::$EMAIL,
            'emailRID' => MappIntelligenceParameter::$EMAIL_RID,
            'emailOptin' => MappIntelligenceParameter::$EMAIL_OPTIN,
            'firstName' => MappIntelligenceParameter::$FIRST_NAME,
            'lastName' => MappIntelligenceParameter::$LAST_NAME,
            'telephone' => MappIntelligenceParameter::$TELEPHONE,
            'gender' => MappIntelligenceParameter::$GENDER,
            'birthday' => MappIntelligenceParameter::$BIRTHDAY,
            'country' => MappIntelligenceParameter::$COUNTRY,
            'city' => MappIntelligenceParameter::$CITY,
            'postalCode' => MappIntelligenceParameter::$POSTAL_CODE,
            'street' => MappIntelligenceParameter::$STREET,
            'streetNumber' => MappIntelligenceParameter::$STREET_NUMBER,
            'validation' => MappIntelligenceParameter::$CUSTOMER_VALIDATION,
            'category' => MappIntelligenceParameter::$CUSTOM_URM_CATEGORY
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
