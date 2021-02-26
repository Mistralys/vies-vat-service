<?php
/**
 * File containing the test class {@see VATTestCase}.
 * @package VIESVATService
 * @subpackage Tests
 */

declare(strict_types=1);

use DragonBe\Vies\Vies;
use Mistralys\VIESVATService\VatEntity;
use Mistralys\VIESVATService\VatID;
use PHPUnit\Framework\TestCase;

/**
 * Base class for all tests in the package's test suites.
 *
 * @package VIESVATService
 * @subpackage Tests
 */
abstract class VATTestCase extends TestCase
{
    /**
     * @var string
     */
    protected $cacheFolder;

    /**
     * @var bool|null
     */
    protected static $isAlive = null;

    protected function setUp(): void
    {
        $this->cacheFolder = __DIR__.'/../cache';
    }

    protected function createTestRequester() : VatEntity
    {
        return new VatEntity(
            VatID::parseID(TESTS_REQUESTER_VATID),
            TESTS_REQUESTER_NAME,
            TESTS_REQUESTER_COMPANY_TYPE,
            TESTS_REQUESTER_STREET,
            TESTS_REQUESTER_POSTCODE,
            TESTS_REQUESTER_VATID
        );
    }

    protected function skipIfNotAlive() : ?Vies
    {
        $vies = $this->createVIES();

        if($vies)
        {
            return $vies;
        }

        $this->markTestSkipped('VIES service is not available');
    }

    protected function createVIES() : ?Vies
    {
        $vies = new DragonBe\Vies\Vies();

        if(!isset(self::$isAlive))
        {
            self::$isAlive = $vies->getHeartBeat()->isAlive();
        }

        if(self::$isAlive)
        {
            return $vies;
        }

        return null;
    }
}
