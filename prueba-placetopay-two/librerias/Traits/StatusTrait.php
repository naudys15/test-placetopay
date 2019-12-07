<?php


namespace Librerias\Traits;


use Librerias\Entities\Status;

trait StatusTrait
{
    /**
     * @var Status
     */
    protected $status;

    /**
     * @return Status
     */
    public function status()
    {
        return $this->status;
    }

}