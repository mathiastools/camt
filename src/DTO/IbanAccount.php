<?php

namespace Genkgo\Camt\DTO;

use Genkgo\Camt\Iban;

/**
 * Class IbanAccount
 * @package Genkgo\Camt\DTO
 */
class IbanAccount extends Account
{
    /**
     * @var Iban
     */
    private $iban;
    
    /** @var string */
    private $currency;

    /**
     * @param Iban $iban
     */
    public function __construct(Iban $iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return Iban
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentification()
    {
        return (string) $this->iban;
    }
    
    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
    
    /**
     * @param string $currency
     *
     * @return IbanAccount
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        
        return $this;
    }
}
