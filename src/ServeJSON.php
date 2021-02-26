<?php

declare(strict_types=1);

namespace Mistralys\VIESVATService;

use AppUtils\OperationResult;

class ServeJSON
{
    const ERROR_FAILED_JSON_ENCODING = 78101;

    const REQUEST_PARAMETER_NAME = 'vatid';
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    const VALIDATION_PARAMETER_MISSING = 78001;

    /**
     * @var Service
     */
    private $service;

    /**
     * @var OperationResult
     */
    private $validation;
    /**
     * @var ServiceResult
     */
    private $result;

    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->validation = new OperationResult($this);

        $this->checkRequest();
    }

    public function hasErrors() : bool
    {
        return !$this->validation->isValid();
    }

    public function getErrorMessage() : string
    {
        return $this->validation->getErrorMessage();
    }

    public function getErrorCode() : int
    {
        return $this->validation->getCode();
    }

    /**
     * @throws Exception
     *
     * @see ServeJSON::ERROR_FAILED_JSON_ENCODING
     */
    public function send() : void
    {
        header('Content-Type:application/json');
        echo $this->getJSON();
    }

    /**
     * @return string
     * @throws Exception
     *
     * @see ServeJSON::ERROR_FAILED_JSON_ENCODING
     */
    public function getJSON() : string
    {
        $json = json_encode($this->getData());

        if($json !== false)
        {
            return $json;
        }

        throw new Exception(
            'Failed to encode JSON data',
            self::ERROR_FAILED_JSON_ENCODING
        );
    }

    /**
     * Retrieves the request data to send as JSON.
     *
     * @return array<string,mixed>
     */
    public function getData() : array
    {
        if ($this->validation->isValid())
        {
            return array(
                'status' => self::STATUS_SUCCESS,
                'data' => $this->result->toArray()
            );
        }

        return array(
            'status' => self::STATUS_ERROR,
            'message' => $this->validation->getMessage(),
            'code' => $this->validation->getCode()
        );
    }

    private function checkRequest() : void
    {
        if(!isset($_REQUEST[self::REQUEST_PARAMETER_NAME]))
        {
            $this->validation->makeError(
                'The VAT ID request parameter is missing.',
                self::VALIDATION_PARAMETER_MISSING
            );
            return;
        }

        $vatID = $_REQUEST[self::REQUEST_PARAMETER_NAME];

        try
        {
            $this->result = $this->service->check(VatID::parseID($vatID));
        }
        catch (Exception $e)
        {
            $this->validation->makeError('Exception thrown: '.$e->getMessage(), $e->getCode());
        }
    }
}