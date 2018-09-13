<?php
/** @noinspection PhpDocMissingThrowsInspection */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Kata\Repository;

use Kata\Entity\Contact;
use Kata\Entity\Invoice;
use PHPUnit\Framework\TestCase;

final class InvoiceRepositoryTest extends TestCase
{
    /**
     * @return InvoiceRepository
     */
    private function getRepository()
    {
        $legacyRepository = $this->getMock(LegacyInvoiceRepository::class);
        $legacyRepository->expects(self::any())
                         ->method('getAll')
                         ->willReturn([
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100),
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100),
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100),
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100),
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100),
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100),
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100),
                             // paid
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100,
                                 new \DateTimeImmutable()),
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100,
                                 new \DateTimeImmutable()),
                             new Invoice(new \DateTimeImmutable('2018-01-11'), new Contact('test', 'test'), 100,
                                 new \DateTimeImmutable()),
                         ]);

        return new InvoiceRepository($legacyRepository);
    }

    /**
     * @test
     */
    public function it_contains_only_unpaid_invoices()
    {
        $invoices = $this->getRepository()->getUnpaidInvoices();

        self::assertNotEmpty($invoices);
        foreach ($invoices as $invoice) {
            self::assertFalse($invoice->isPaid());
        }
    }
}
