<?php

namespace Ddeboer\Vatin\Vies;

use Ddeboer\Vatin\Vies\Soap\TimeOutSoapClient;
use SoapFault;
use Ddeboer\Vatin\Exception\ViesException;

/**
 * A client for the VIES SOAP web service
 */
class Client
{
    /**
     * URL to WSDL
     *
     * @var string
     */
    private $wsdl = 'https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    /**
     * SOAP client
     *
     * @var \SoapClient
     */
    private $soapClient;

    private $timeout;

    /**
     * Constructor
     *
     * @param string|null $wsdl URL to WSDL
     * @param int $timeout
     */
    public function __construct($timeout = 5, $wsdl = null)
    {
        if ($wsdl) {
            $this->wsdl = $wsdl;
        }

        $this->timeout = $timeout;
    }

    /**
     * Check VAT
     *
     * @param string $countryCode Country code
     * @param string $vatNumber   VAT number
     *
     * @return object
     * @throws ViesException
     */
    public function checkVat($countryCode, $vatNumber)
    {
        try {
            return $this->getSoapClient()->checkVat(
                [
                    'countryCode' => $countryCode,
                    'vatNumber' => $vatNumber
                ]
            );
        } catch (SoapFault $e) {
            throw new ViesException('Error communicating with VIES service', 0, $e);
        }
    }

    /**
     * Get SOAP client
     *
     * @return \SoapClient
     * @throws \SoapFault
     */
    private function getSoapClient()
    {
        if (null === $this->soapClient) {
            $this->soapClient = new TimeOutSoapClient(
                $this->wsdl,
                [
                    'user_agent' => 'Mozilla', // the request fails unless a (dummy) user agent is specified
                    'exceptions' => true,
                ]
            );
        }

        return $this->soapClient;
    }
}
