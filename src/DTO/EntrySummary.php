<?php 
namespace Genkgo\Camt\DTO;

use Money\Currency;
use Money\Money;

/**
 * Class SummaryEntry
 * @package Genkgo\Camt\DTO
 */
abstract class EntrySummary
{
    /** @var int */
    protected $numberOfEntries;
    
    /** @var Money */
    protected $sum;
    
    /**
     * EntrySummary constructor.
     *
     * @param string $numberOfEntries
     */
    public function __construct($numberOfEntries)
    {
        $this->numberOfEntries = (int)$numberOfEntries;
    }
    
    /**
     * @return int
     */
    public function getNumberOfEntries()
    {
        return $this->numberOfEntries;
    }
    
    /**
     * @return Money
     */
    public function getSum()
    {
        return $this->sum;
    }
    
    /**
     * @param string $sumString
     * @param string $currencyString
     *
     * @throws \Money\UnknownCurrencyException
     */
    public function setSum($sumString, $currencyString)
    {
        $currency = new Currency(strtoupper($currencyString));
        $sumUnits = Money::stringToUnits($sumString);
        
        $this->sum = new Money($sumUnits, $currency);
    }
    
    
}