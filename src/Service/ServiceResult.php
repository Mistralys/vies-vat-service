<?php

declare(strict_types=1);

namespace Mistralys\VIESVATService;

use DragonBe\Vies\CheckVatResponse;

class ServiceResult
{
    /**
     * @var CheckVatResponse
     */
    private $response;

    /**
     * @var Service
     */
    private $vat;

    /**
     * @var VatID
     */
    private $target;

    public function __construct(Service $vat, VatID $target, CheckVatResponse $response)
    {
        $this->vat = $vat;
        $this->response = $response;
        $this->target = $target;
    }

    public function getResponse() : CheckVatResponse
    {
        return $this->response;
    }

    /**
     * Serializes the result to an associative array.
     *
     * It has the following structure:
     *
     * <pre>
     * 'result' => array(
     *     'vatID' => array(
     *         'countryCode' => 'FR',
     *         'number' => '12345678',
     *     ),
     *     'valid' => true,
     *     'identifier' => '',
     *     'date' => '2021-02-11 00:00:00',
     *     'companyName' => '',
     *     'companyAddress' => ''
     * ),
     * 'requester' => array(
     *     'name' => '',
     *     'companyType' => '',
     *     'street' => '',
     *     'postcode' => '',
     *     'city' => '',
     *     'vatID' => array(
     *         'countryCode' => 'FR',
     *         'number' => '12345678',
     *     ),
     * ),
     * 'requesterMatch' => array(
     *     'name' => '',
     *     'companyType' => '',
     *     'street' => '',
     *     'postcode' => '',
     *     'city' => ''
     * )
     * </pre>
     *
     * @return array<string,string|<array<string,mixed>>
     */
    public function toArray() : array
    {
        return array(
            'result' => array(
                'vatID' => $this->target->toArray(),
                'valid' => $this->response->isValid(),
                'identifier' => $this->response->getIdentifier(),
                'date' => $this->response->getRequestDate()->format(\DateTimeInterface::ISO8601),
                'companyName' => trim($this->response->getName(), '-'),
                'companyAddress' => trim($this->response->getAddress(), '-'),
            ),
            'requester' => $this->vat->getRequester()->toArray(),
            'requesterMatch' => array(
                'name' => $this->response->getNameMatch(),
                'companyType' => $this->response->getCompanyTypeMatch(),
                'street' => $this->response->getStreetMatch(),
                'postcode' => $this->response->getPostcodeMatch(),
                'city' => $this->response->getCityMatch()
            )
        );
    }
}
