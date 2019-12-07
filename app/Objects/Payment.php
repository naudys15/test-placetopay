<?php
namespace App\Objects;

class Payment
{
    var $reference;
    var $description;
    var $currency;
    var $total;

    public function __construct($reference, $description, $currency, $total)
    {
        $this->reference = $reference;
        $this->description = $description;
        $this->currency = $currency;
        $this->total = $total;
    }

    public function getPayment()
    {
        $payment = array(
            'reference' => $this->reference,
            'description' => $this->description,
            'amount' => array(
                'currency' => $this->currency,
                'total' => $this->total
            )
        );
        return $payment;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getTotal()
    {
        return $this->total;
    }
}