<?php

namespace Kata;

use Kata\Entity\Invoice;

final class InvoiceHandler
{
    const DAYS_BEFORE_PAYMENT_NOTIFICATION = 10;

    /**
     * @param Invoice            $invoice
     * @param \DateTimeImmutable $today
     *
     * @return bool
     */
    public function handle(Invoice $invoice, \DateTimeImmutable $today)
    {
        if ($invoice->isPaid()) {
            return false;
        }

        if ($this->isPaymentNotificationDay($invoice, $today)) {
            return true;
        }

        if ($this->isPaymentRevivalDay($invoice, $today)) {
            return true;
        }

        return false;
    }

    /**
     * @param Invoice            $invoice
     * @param \DateTimeImmutable $today
     *
     * @return bool
     */
    public function isPaymentNotificationDay(Invoice $invoice, \DateTimeImmutable $today)
    {
        $notificationDay = $this->getNotificationDate($invoice->getDueDate());

        return $notificationDay->format('Ymd') === $today->format('Ymd');
    }

    /**
     * @param Invoice            $invoice
     * @param \DateTimeImmutable $today
     *
     * @return bool
     */
    public function shouldHaveBeenPaid(Invoice $invoice, \DateTimeImmutable $today)
    {
        if ($invoice->isPaid()) {
            return false;
        }

        return $today > $invoice->getDueDate();
    }

    /**
     * @param \DateTimeImmutable $dueDate
     * @param \DateTimeImmutable $today
     *
     * @return bool
     */
    public function isARevivalDay(\DateTimeImmutable $dueDate, \DateTimeImmutable $today)
    {
        $notificationDate = $this->getNotificationDate($dueDate);

        if ($today->format('d') === $notificationDate->format('d')) {
            return true;
        }

        $checkLastDayOfMonth = \DateTime::createFromFormat(\DateTime::ATOM, $today->format(\DateTime::ATOM));
        $checkLastDayOfMonth->setDate(
            $today->format('Y'),
            $today->format('m'),
            $notificationDate->format('d')
        );

        if ($today->format('m') === $checkLastDayOfMonth->format('m')) {
            return false;
        }

        return (int) $today->format('d') === $this->getLastDayOfMonth($today);
    }

    /**
     * @param \DateTimeImmutable $dueDate
     *
     * @return \DateTime
     */
    private function getNotificationDate(\DateTimeImmutable $dueDate)
    {
        $notificationDay = new \DateTime($dueDate->format(\DateTime::ATOM));
        $notificationDay->modify('-' . self::DAYS_BEFORE_PAYMENT_NOTIFICATION . ' days');

        return $notificationDay;
    }

    /**
     * @param Invoice            $invoice
     * @param \DateTimeImmutable $today
     *
     * @return bool
     */
    private function isPaymentRevivalDay(Invoice $invoice, \DateTimeImmutable $today)
    {
        return $this->shouldHaveBeenPaid($invoice, $today) && $this->isARevivalDay($invoice->getDueDate(), $today);
    }

    /**
     * @param \DateTimeImmutable $today
     *
     * @return int
     */
    private function getLastDayOfMonth(\DateTimeImmutable $today)
    {
        $lastDayOfMonth = \DateTime::createFromFormat(\DateTime::ATOM, $today->format(\DateTime::ATOM));

        $lastDayOfMonth->setDate(
            $today->format('Y'),
            (int) $today->format('m') + 1,
            1
        );
        $lastDayOfMonth->modify('-1 day');

        return (int) $lastDayOfMonth->format('d');
    }

}
