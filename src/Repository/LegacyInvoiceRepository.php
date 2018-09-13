<?php

namespace Kata\Repository;

use Kata\Entity\Invoice;

interface LegacyInvoiceRepository
{

    /**
     * @return Invoice[]
     */
    public function getAll();

}
