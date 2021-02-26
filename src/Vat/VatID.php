<?php

declare(strict_types=1);

namespace Mistralys\VIESVATService;

use AppUtils\ConvertHelper;

class VatID
{
    const ERROR_EMPTY_VATID_STRING = 77901;
    const ERROR_VATID_STRING_TOO_SHORT = 77902;
    const ERROR_INVALID_COUNTRY_CODE = 77903;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @var string
     */
    private $number;

    public function __construct(string $countryCode, string $number)
    {
        $this->countryCode = $countryCode;
        $this->number = $number;
    }

    public function getCountryCode() : string
    {
        return $this->countryCode;
    }

    public function getNumber() : string
    {
        return $this->number;
    }

    public function getID() : string
    {
        return $this->countryCode.$this->getID();
    }

    public function toArray() : array
    {
        return array(
            'countryCode' => $this->getCountryCode(),
            'number' => $this->getNumber()
        );
    }

    /**
     * Takes a full VAT ID string like `FR12345678` and
     * returns a VatID instance for it.
     *
     * @param string $idString
     * @return VatID
     * @throws Exception
     */
    public static function parseID(string $idString) : VatID
    {
        $idString = self::cleanID($idString);

        $countryCode = strtoupper(substr($idString, 0, 2));

        if(!preg_match('/^[A-Z]{2}\z/x', $countryCode))
        {
            throw new Exception(
                'Invalid country code.',
                self::ERROR_INVALID_COUNTRY_CODE
            );
        }

        $number = substr($idString, 2);

        return new VatID($countryCode, $number);
    }

    /**
     * @param string $idString
     * @return string
     * @throws Exception
     */
    private static function cleanID(string $idString) : string
    {
        // Strip out all whitespace chars
        $idString = preg_replace('/\s/', '', $idString);

        if(empty($idString))
        {
            throw new Exception(
                'Empty VAT ID string given.',
                self::ERROR_EMPTY_VATID_STRING
            );
        }

        $idString = str_replace(array('-', '.', '/', '|', '(', ')', '[', ']', '●', '_'), '', trim($idString));

        // Min length of the number for all countries is 8.
        // https://stackoverflow.com/questions/33625770/check-vat-number-for-syntactical-correctness-with-regex-possible/33627030
        if(strlen($idString) >= 10) {
            return $idString;
        }

        throw new Exception(
            'ID string is too short.',
            self::ERROR_VATID_STRING_TOO_SHORT
        );
    }
}
