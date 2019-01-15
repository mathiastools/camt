<?php

namespace Genkgo\Camt\DTO;

use DateTimeImmutable;
use Money\Money;

/**
 * Class Balance
 * @package Genkgo\Camt\DTO
 */
class Balance
{
    /**
     *
     */
    const TYPE_OPENING = 'opening';
    /**
     *
     */
    const TYPE_CLOSING = 'closing';

    /**
     * @var Money
     */
    private $amount;
    
    /**
     * @var string
     */
    private $type;
    
    /**
     * @var string
     */
    private $code;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @param string $type
     * @param string $code
     * @param Money $amount
     * @param DateTimeImmutable $date
     */
    private function __construct($type, $code, Money $amount, DateTimeImmutable $date)
    {
        $this->type = $type;
        $this->code = $code;
        $this->amount = $amount;
        $this->date = $date;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return Money
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param Money             $amount
     * @param DateTimeImmutable $date
     * @param string            $code
     *
     * @return static
     */
    public static function opening(Money $amount, DateTimeImmutable $date, $code)
    {
        return new static (self::TYPE_CLOSING, $code, $amount, $date);
    }

    /**
     * @param Money             $amount
     * @param DateTimeImmutable $date
     * @param string            $code
     *
     * @return static
     */
    public static function closing(Money $amount, DateTimeImmutable $date, $code)
    {
        return new static (self::TYPE_CLOSING, $code, $amount, $date);
    }
}
