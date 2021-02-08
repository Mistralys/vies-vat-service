<?php

if(!isset($_REQUEST['vatid'])) {
    die('No vat ID specified.');
}

ini_set('display_errors', 1);error_reporting(E_ALL);

$urlWSDL = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';
$urlEndpoint = 'http://ec.europa.eu/taxation_customs/vies/services/checkVatService';

if(!isset($_REQUEST['vatid'])) {
	die('No VATID specified in the vatid parameter.');
}

$vatID = $_REQUEST['vatid'];
$country = substr($vatID, 0, 2);
$number = substr($vatID, 2);
$cacheExpiry = 60 * 60 * 4;
$time = new DateTime();
$cacheFile = 'cache/'.$vatID.'.json';

if(isset($_REQUEST['refresh']) && $_REQUEST['refresh'] == 'yes') {
	@unlink($cacheFile);
}

try
{
	if(file_exists($cacheFile) && filemtime($cacheFile) + $cacheExpiry > time())
	{
		$json = file_get_contents($cacheFile);
	}
	else
	{
		$client = new SoapClient($urlWSDL);

		$info = $client->checkVatApprox(array(
			'countryCode' => $country,
			'vatNumber' => $number,
			'requesterCountryCode' => 'FR',
			'requesterVatNumber' => '27803978782'
		));

		$info->urlWSDL = $urlWSDL;
		$info->urlEndpoint = $urlEndpoint;
		$info->requestDate = $time->format('Y-m-d H:i:s');
		$info->vatID = $vatID;
		$info->error = false;
		
		if(!isset($info->traderName)) {
			$info->traderName = '';
		}
		
		if(!isset($info->traderAddress)) {
			$info->traderAddress = '';
		}
		
		if($info->traderName == '---') {
			$info->traderName = '';
		}
		
		if($info->traderAddress == '---') {
			$info->traderAddress = '';
		}
		
		$info->name = $info->traderName;
		$info->address = $info->traderAddress;

		$json = json_encode($info);
		
		header('Content-Type:application/json; charset=UTF-8');
		
		file_put_contents($cacheFile, $json);
	}
}
catch(Throwable $e)
{
	$json = json_encode(array(
		'urlWSDL' => $urlWSDL,
		'urlEndpoint' => $urlEndpoint,
		'requestDate' => $time->format('Y-m-d H:i:s'),
		'vatID' => $vatID,
		'valid' => false,
		'error' => true,
		'errorType' => get_class($e),
		'errorMessage' => $e->getMessage(),
		'errorCode' => $e->getCode()
	));
}

header('Content-Type:text/json');

echo $json;
