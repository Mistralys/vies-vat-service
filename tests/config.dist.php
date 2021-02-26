<?php
/**
 * Testsuite configuration file
 *
 * @package VIESVATService
 * @subpackage Tests
 */

// The tests require a valid requesting company to fetch
// data from the live VIES service.

    /** Company name */
    define('TESTS_REQUESTER_NAME', '');

    /** Full VAT ID string, e.g. "FR12345678" */
    define('TESTS_REQUESTER_VATID', '');

    /** Street address */
    define('TESTS_REQUESTER_STREET', '');

    /** City name */
    define('TESTS_REQUESTER_CITY', '');

    /** City postal code */
    define('TESTS_REQUESTER_POSTCODE', '');

    /**
     * Company type code - only relevant for some countries.
     * @link https://ec.europa.eu/taxation_customs/vies/faq.html?locale=en#item_25
     */
    define('TESTS_REQUESTER_COMPANY_TYPE', '');
