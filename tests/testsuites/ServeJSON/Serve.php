<?php

use Mistralys\VIESVATService\ServeJSON;
use Mistralys\VIESVATService\Service;
use Mistralys\VIESVATService\VatID;

class ServeJSON_ServeTests extends VATTestCase
{
    public function test_parse()
    {
        $tests = array(
            array(
                'label' => 'Request var missing',
                'var' => null,
                'errors' => true,
                'code' => ServeJSON::VALIDATION_PARAMETER_MISSING,
                'status' => ServeJSON::STATUS_ERROR
            ),
            array(
                'label' => 'Empty request var',
                'var' => '',
                'errors' => true,
                'code' => VatID::ERROR_EMPTY_VATID_STRING,
                'status' => ServeJSON::STATUS_ERROR
            ),
            array(
                'label' => 'Invalid request var',
                'var' => '1234567890',
                'errors' => true,
                'code' => VatID::ERROR_INVALID_COUNTRY_CODE,
                'status' => ServeJSON::STATUS_ERROR
            ),
            array(
                'label' => 'Valid variable',
                'var' => 'FR12345678',
                'errors' => false,
                'code' => 0,
                'status' => ServeJSON::STATUS_SUCCESS
            )
        );

        foreach ($tests as $test)
        {
            $_REQUEST = array();

            if(isset($test['var'])) {
                $_REQUEST[ServeJSON::REQUEST_PARAMETER_NAME] = $test['var'];
            }

            $vies = $this->skipIfNotAlive();
            $serve = new ServeJSON(new Service($this->cacheFolder, $this->createTestRequester(), $vies));

            $label = $test['label'].PHP_EOL.
            'Error message: ['.$serve->getErrorMessage().']'.PHP_EOL.
            'Error code: ['.$serve->getErrorCode().']'.PHP_EOL;

            $this->assertSame($test['errors'], $serve->hasErrors(), $label);

            $data = $serve->getData();
            $this->assertSame($test['status'], $data['status'], $label);
        }
    }

    public function test_fetchData() : void
    {
        $_REQUEST[ServeJSON::REQUEST_PARAMETER_NAME] = TESTS_REQUESTER_VATID;

        $vies = $this->skipIfNotAlive();

        $serve = new ServeJSON(new Service($this->cacheFolder, $this->createTestRequester(), $vies));

        $this->assertFalse($serve->hasErrors());

        $data = $serve->getData();

        $this->assertNotEmpty($data['data']['result']['identifier']);
        $this->assertNotEmpty($data['data']['result']['date']);
    }
}
