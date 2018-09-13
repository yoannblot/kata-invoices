<?php
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Kata;

use Kata\Entity\Contact;
use Kata\Entity\Invoice;
use PHPUnit\Framework\TestCase;

final class InvoiceHandlerTest extends TestCase
{

    /**
     * @test
     */
    public function it_does_not_handle_a_paid_invoice()
    {
        $dueDate  = new \DateTimeImmutable('2018-01-02');
        $paidDate = new \DateTimeImmutable('2018-01-01');
        $invoice  = new Invoice($dueDate, new Contact('test', 'test'), 100, $paidDate);

        $handler = new InvoiceHandler();
        self::assertFalse($handler->handle($invoice, new \DateTimeImmutable()));
    }

    /**
     * @test
     */
    public function it_should_have_been_paid_when_due_date_is_expired()
    {
        $invoice = new Invoice(new \DateTimeImmutable('2018-01-01'), new Contact('test', 'test'), 100, null);

        $handler = new InvoiceHandler();
        self::assertTrue($handler->shouldHaveBeenPaid($invoice, new \DateTimeImmutable('2018-01-02')));
    }

    /**
     * @test
     */
    public function it_should_not_have_been_paid()
    {
        $invoice = new Invoice(new \DateTimeImmutable('2018-01-02'), new Contact('test', 'test'), 100, null);
        $handler = new InvoiceHandler();

        self::assertFalse($handler->shouldHaveBeenPaid($invoice, new \DateTimeImmutable('2018-01-01')));
    }

    /**
     * @test
     */
    public function it_is_payment_notification_day()
    {
        $invoice = new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100, null);
        $today   = new \DateTimeImmutable('2018-01-01');
        $handler = new InvoiceHandler();

        self::assertTrue($handler->isPaymentNotificationDay($invoice, $today));
    }

    /**
     * @test
     */
    public function it_is_not_payment_notification_day()
    {
        $invoice = new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100, null);
        $today   = new \DateTimeImmutable('2018-01-03');
        $handler = new InvoiceHandler();

        self::assertFalse($handler->isPaymentNotificationDay($invoice, $today));
    }

    /**
     * @test
     */
    public function it_is_payment_notification_day_when_month_changes()
    {
        $invoice = new Invoice(new \DateTimeImmutable('2018-03-01'), new Contact('test', 'test'), 100, null);
        $today   = new \DateTimeImmutable('2018-02-19');
        $handler = new InvoiceHandler();

        self::assertTrue($handler->isPaymentNotificationDay($invoice, $today));
    }

    /**
     * @test
     * @dataProvider simpleRevivalDaysProvider
     *
     * @param string $dateFormat
     */
    public function it_is_payment_revival_day($dateFormat)
    {
        $dueDate = new \DateTimeImmutable('2018-03-01');
        $today   = new \DateTimeImmutable($dateFormat);
        $handler = new InvoiceHandler();

        self::assertTrue($handler->isARevivalDay($dueDate, $today));
    }

    /**
     * @return array
     */
    public function simpleRevivalDaysProvider()
    {
        return [
            'first month'  => ['2018-03-19'],
            'second month' => ['2018-04-19'],
            'third month'  => ['2018-05-19']
        ];
    }

    /**
     * @test
     * @dataProvider lastDayOfMonthRevivalDaysProvider
     *
     * @param string $dateFormat
     */
    public function it_is_payment_revival_day_on_last_month_day_of_due_date($dateFormat)
    {
        $dueDate = new \DateTimeImmutable('2018-02-10');
        $today   = new \DateTimeImmutable($dateFormat);
        $handler = new InvoiceHandler();

        self::assertTrue($handler->isARevivalDay($dueDate, $today));
    }

    /**
     * @return array
     */
    public function lastDayOfMonthRevivalDaysProvider()
    {
        return [
            'january contains 31 days'   => ['2018-01-31'],
            'august contains 31 days'    => ['2018-08-31'],
            'september contains 30 days' => ['2018-09-30'],
            'february contains 28 days'  => ['2018-02-28'],
        ];
    }
}
