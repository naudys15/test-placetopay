<?php


namespace Librerias\Entities;


use Librerias\Contracts\Entity;
use Librerias\Traits\LoaderTrait;

class AmountDetail extends Entity
{
    use LoaderTrait;

    protected $kind;
    protected $amount;

    public function __construct($data = [])
    {
        $this->load($data, ['kind', 'amount']);
    }

    public function kind()
    {
        return $this->kind;
    }

    public function amount()
    {
        return $this->amount;
    }

    public function toArray()
    {
        return $this->arrayFilter([
            'kind' => $this->kind(),
            'amount' => $this->amount(),
        ]);
    }

}