<?php

declare(strict_types=1);

namespace Mistralys\VIESVATService;

class VatEntity
{
    /**
     * @var VatID
     */
    private $vatid;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $companyType;
    /**
     * @var string
     */
    private $street;
    /**
     * @var string
     */
    private $postcode;
    /**
     * @var string
     */
    private $city;

    public function __construct(VatID $vatID, string $name, string $companyType, string $street, string $postcode, string $city)
    {
        $this->vatid = $vatID;
        $this->name = $name;
        $this->companyType = $companyType;
        $this->street = $street;
        $this->postcode = $postcode;
        $this->city = $city;
    }

    public function getVatNumber() : string
    {
        return $this->vatid->getNumber();
    }

    public function getVatCountryCode() : string
    {
        return $this->vatid->getCountryCode();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getCompanyType(): string
    {
        return $this->companyType;
    }

    /**
     * @return string
     */
    public function getPostcode(): string
    {
        return $this->postcode;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @return VatID
     */
    public function getVatID(): VatID
    {
        return $this->vatid;
    }

    public function toArray() : array
    {
        return array(
            'name' => $this->getName(),
            'companyType' => $this->getCompanyType(),
            'street' => $this->getStreet(),
            'city' => $this->getCity(),
            'postcode' => $this->getPostcode(),
            'vatID' => $this->vatid->toArray()
        );
    }
}
