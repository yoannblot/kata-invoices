<?php

namespace Kata\Entity;

final class Invoice
{
    /** @var \DateTimeImmutable */
    private $dueDate;

    /** @var \DateTimeImmutable|null */
    private $paymentDate;

    /** @var Contact */
    private $contact;

    /** @var \float */
    private $price;

    /**
     * Invoice constructor.
     *
     * @param \DateTimeImmutable      $dueDate
     * @param Contact                 $contact
     * @param float                   $price
     * @param \DateTimeImmutable|null $paymentDate
     */
    public function __construct(
        \DateTimeImmutable $dueDate,
        Contact $contact,
        $price,
        \DateTimeImmutable $paymentDate = null
    ) {
        $this->dueDate     = $dueDate;
        $this->contact     = $contact;
        $this->price       = $price;
        $this->paymentDate = $paymentDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return $this->paymentDate !== null;
    }
}
