<?php

namespace Kata\Repository;

use Kata\Entity\Invoice;

final class InvoiceRepository
{
    /** @var LegacyInvoiceRepository */
    private $legacyInvoiceRepository;

    /**
     * InvoiceRepository constructor.
     *
     * @param LegacyInvoiceRepository $legacyInvoiceRepository
     */
    public function __construct(LegacyInvoiceRepository $legacyInvoiceRepository)
    {
        $this->legacyInvoiceRepository = $legacyInvoiceRepository;
    }

    /**
     * @return Invoice[]
     */
    public function getUnpaidInvoices()
    {
        return array_filter(array_map(function (Invoice $invoice) {
            if ($invoice->isPaid()) {
                return null;
            }

            return $invoice;
        }, $this->legacyInvoiceRepository->getAll()));
    }
}
