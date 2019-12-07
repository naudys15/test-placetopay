<?php


namespace Librerias\Message;


use Librerias\Contracts\Entity;
use Librerias\Entities\Status;
use Librerias\Traits\LoaderTrait;
use Librerias\Traits\StatusTrait;

class RedirectResponse extends Entity
{

    use LoaderTrait, StatusTrait;
    /**
     * @var string
     */
    public $requestId;
    /**
     * @var string
     */
    public $processUrl;

    public function __construct($data = [])
    {
        $this->load($data, ['requestId', 'processUrl']);
        if (isset($data['status']))
            $this->setStatus($data['status']);
    }

    /**
     * Unique transaction code for this request
     * @return string
     */
    public function requestId()
    {
        return $this->requestId;
    }

    /**
     * URL to consume when the gateway requires redirection
     * @return string|null
     */
    public function processUrl()
    {
        return $this->processUrl;
    }

    public function isSuccessful()
    {
        return $this->status()->status() == Status::ST_OK;
    }

    /**
     * Returns the contents for this response as an array
     * @return array
     */
    public function toArray()
    {
        return $this->arrayFilter([
            'status' => $this->status() ? $this->status()->toArray() : null,
            'requestId' => $this->requestId(),
            'processUrl' => $this->processUrl(),
        ]);
    }

}