<?php

namespace Ddeboer\Vatin\Vies\Soap;

/**
 *
 */
class TimeOutSoapClient extends \SoapClient
{
    /**
     * @var
     */
    private $timeout;

    /**
     * @param $wsdl
     * @param array $options
     * @throws \SoapFault
     */
    public function __construct($wsdl, array $options)
    {
        if (!isset($options['connection_timeout'])) {
            $options['connection_timeout'] = $this->timeout;
        }

        parent::__construct($wsdl, $options);
    }

    /**
     * @param $timeout
     * @return void
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @param $request
     * @param $location
     * @param $action
     * @param $version
     * @param $oneWay
     * @return string|null
     */
    public function __doRequest($request, $location, $action, $version, $oneWay = false)
    {
        $original = ini_get('default_socket_timeout');
        ini_set('default_socket_timeout', $this->timeout);
        $response = parent::__doRequest($request, $location, $action, $version, $oneWay);
        ini_set('default_socket_timeout', $original);

        return $response;
    }
}
