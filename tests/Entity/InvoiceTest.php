<?php
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Kata\Entity;

use PHPUnit\Framework\TestCase;

final class InvoiceTest extends TestCase
{
    /**
     * @test
     */
    public function it_is_not_paid()
    {
        $invoice = new Invoice(new \DateTimeImmutable(), new Contact('test', 'test'), 100, null);

        self::assertFalse($invoice->isPaid());
    }

    /**
     * @test
     */
    public function it_is_paid()
    {
        $dueDate  = new \DateTimeImmutable('2018-01-02');
        $paidDate = new \DateTimeImmutable('2018-01-01');
        $invoice  = new Invoice($dueDate, new Contact('test', 'test'), 100, $paidDate);

        self::assertTrue($invoice->isPaid());
    }
}
