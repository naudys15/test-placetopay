<?php
namespace Librerias;

use Librerias\Carrier\Authentication;
use Librerias\Carrier\RestCarrier;
use Librerias\Carrier\SoapCarrier;
use Librerias\Contracts\Carrier;
use Librerias\Contracts\Gateway;
use Librerias\Exceptions\PlacetoPayException;
use Librerias\Message\CollectRequest;
use Librerias\Message\RedirectInformation;
use Librerias\Message\RedirectRequest;
use Librerias\Message\RedirectResponse;
use Librerias\Message\ReverseResponse;

class PlacetoPay
{

    private function carrier()
    {
        if ($this->carrier instanceof Carrier)
            return $this->carrier;

        $config = $this->config;
        $auth = new Authentication($config);
        $type = $this->type;
        $typeConfig = isset($config[$type]) ? $config[$type] : [];

        if ($type == self::TP_SOAP) {
            $carrierConfig = array_merge([
                'wsdl' => $config['url'] . 'soap/redirect?wsdl',
                'location' => $config['url'] . 'soap/redirect',
            ], $typeConfig);
            $this->carrier = new SoapCarrier($auth, $carrierConfig);
        } else {
            $carrierConfig = array_merge([
                'url' => $config['url'],
            ], $typeConfig);
            $this->carrier = new RestCarrier($auth, $carrierConfig);
        }

        return $this->carrier;
    }

    /**
     * @param RedirectRequest|array $redirectRequest
     * @return RedirectResponse
     * @throws PlacetoPayException
     */
    public function request($redirectRequest)
    {
        if (is_array($redirectRequest))
            $redirectRequest = new RedirectRequest($redirectRequest);

        if (!($redirectRequest instanceof RedirectRequest))
            throw new PlacetoPayException('Wrong class request');

        return $this->carrier()->request($redirectRequest);
    }

    /**
     * @param int $requestId
     * @return RedirectInformation
     */
    public function query($requestId)
    {
        return $this->carrier()->query($requestId);
    }

    /**
     * @param CollectRequest|array $collectRequest
     * @return RedirectInformation
     * @throws PlacetoPayException
     */
    public function collect($collectRequest)
    {
        if (is_array($collectRequest))
            $collectRequest = new CollectRequest($collectRequest);

        if (!($collectRequest instanceof CollectRequest))
            throw new PlacetoPayException('Wrong collect request');

        return $this->carrier()->collect($collectRequest);
    }

    /**
     * @param string $internalReference
     * @return ReverseResponse
     */
    public function reverse($internalReference)
    {
        return $this->carrier()->reverse($internalReference);
    }

}