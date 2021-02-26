<?php

use Mistralys\VIESVATService\VatID;

class VATIDs_ParseTests extends VATTestCase
{
    public function test_parse()
    {
        $tests = array(
            array(
                'label' => 'Empty string',
                'id' => '',
                'exception' => VatID::ERROR_EMPTY_VATID_STRING,
                'code' => '',
                'number' => ''
            ),
            array(
                'label' => 'Whitespace only string',
                'id' => '               ',
                'exception' => VatID::ERROR_EMPTY_VATID_STRING,
                'code' => '',
                'number' => ''
            ),
            array(
                'label' => 'ID too short',
                'id' => 'FR1234567',
                'exception' => VatID::ERROR_VATID_STRING_TOO_SHORT,
                'code' => '',
                'number' => ''
            ),
            array(
                'label' => 'No country code',
                'id' => '1234567890',
                'exception' => VatID::ERROR_INVALID_COUNTRY_CODE,
                'code' => '',
                'number' => ''
            ),
            array(
                'label' => 'Lowercase country code',
                'id' => 'fr12345678',
                'exception' => 0,
                'code' => 'FR',
                'number' => '12345678'
            ),
            array(
                'label' => 'Strip common separator characters',
                'id' => '[FR]-12.34/56_(78)',
                'exception' => 0,
                'code' => 'FR',
                'number' => '12345678'
            )
        );

        foreach ($tests as $test)
        {
            try
            {
                $parsed = VatID::parseID($test['id']);

                $this->assertEquals($test['code'], $parsed->getCountryCode());
                $this->assertEquals($test['number'], $parsed->getNumber());
            }
            catch (\Mistralys\VIESVATService\Exception $e)
            {
                if($test['exception'] === 0) {
                    $this->fail('Exception thrown, none expected.');
                }

                if($test['exception'] !== $e->getCode()) {
                    $this->fail(sprintf(
                        '%sException code [%s] does not match the expected code [%s].',
                        $test['label'].PHP_EOL,
                        $e->getCode(),
                        $test['exception']
                    ));
                }

                $this->addToAssertionCount(1);
            }
        }
    }
}
