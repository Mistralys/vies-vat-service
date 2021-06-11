<?php

declare(strict_types=1);

namespace Mistralys\VIESVATService;

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;

class Service
{
    const ERROR_VIES_SERVICE_OFFLINE = 77701;
    const ERROR_VIES_SERVICE_ERROR = 77702;

    /**
     * @var string
     */
    private $cacheFolder;

    /**
     * @var VatEntity
     */
    private $requester;

    /**
     * @var Vies
     */
    private $vies;

    public function __construct(string $cacheFolder, VatEntity $requester, Vies $vies)
    {
        $this->cacheFolder = $cacheFolder;
        $this->requester = $requester;
        $this->vies = $vies;
    }

    /**
     * @return VatEntity
     */
    public function getRequester(): VatEntity
    {
        return $this->requester;
    }

    /**
     * @param VatID $target
     * @return ServiceResult
     * @throws Exception
     *
     * @see Service::ERROR_VIES_SERVICE_ERROR
     * @see Service::ERROR_VIES_SERVICE_OFFLINE
     */
    public function check(VatID $target) : ServiceResult
    {
        $heartBeat = $this->vies->getHeartBeat();

        if(!$heartBeat->isAlive()) {
            throw new Exception(
                sprintf(
                    'VIES service is not available: it seems to be offline, no heartbeat detected on host %1$s:%2$s.',
                    $heartBeat->getHost(),
                    $heartBeat->getPort()
                ),
                self::ERROR_VIES_SERVICE_OFFLINE
            );
        }

        try
        {
            $result = $this->vies->validateVat(
                $target->getCountryCode(),
                $target->getNumber(),
                $this->requester->getVatCountryCode(),
                $this->requester->getVatNumber(),
                $this->requester->getName(),
                $this->requester->getCompanyType(),
                $this->requester->getStreet(),
                $this->requester->getPostcode(),
                $this->requester->getCity()
            );

            return new ServiceResult($this, $target, $result);
        }
        catch (ViesException | ViesServiceException $e)
        {
            throw new Exception(
                'VIES Backend Exception: '.$e->getMessage(),
                self::ERROR_VIES_SERVICE_ERROR,
                $e
            );
        }
    }
}
