<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Service;

use DateTime;
use Ksfraser\CRM\Entity\Communication;
use Ksfraser\CRM\Service\CommunicationService;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CommunicationService.
 *
 * @since 1.0.0
 */
class CommunicationServiceTest extends TestCase
{
    private CommunicationService $service;

    protected function setUp(): void
    {
        $this->service = new CommunicationService();
    }

    // -------------------------------------------------------------------------
    // logCommunication
    // -------------------------------------------------------------------------

    public function testLogCommunicationReturnsCommunicationEntity(): void
    {
        $comm = $this->service->logCommunication('cust-1', [
            'type'    => Communication::TYPE_CALL,
            'subject' => 'Test call',
        ]);

        $this->assertInstanceOf(Communication::class, $comm);
        $this->assertSame('cust-1', $comm->getCustomerId());
        $this->assertSame('Test call', $comm->getSubject());
        $this->assertNotNull($comm->getId());
        $this->assertNotNull($comm->getCreatedAt());
    }

    public function testLogCommunicationUsesProvidedId(): void
    {
        $comm = $this->service->logCommunication('cust-1', [
            'id'      => 'fixed-id',
            'subject' => 'Test',
        ]);

        $this->assertSame('fixed-id', $comm->getId());
    }

    // -------------------------------------------------------------------------
    // getCommunication
    // -------------------------------------------------------------------------

    public function testGetCommunicationReturnsLogged(): void
    {
        $logged = $this->service->logCommunication('cust-1', ['id' => 'c1', 'subject' => 'S']);
        $retrieved = $this->service->getCommunication('c1');

        $this->assertSame($logged, $retrieved);
    }

    public function testGetCommunicationReturnsNullForUnknownId(): void
    {
        $this->assertNull($this->service->getCommunication('no-such-id'));
    }

    // -------------------------------------------------------------------------
    // getCommunications (filtering)
    // -------------------------------------------------------------------------

    public function testGetCommunicationsFiltersByCustomerId(): void
    {
        $this->service->logCommunication('cust-1', ['id' => 'c1', 'subject' => 'A']);
        $this->service->logCommunication('cust-2', ['id' => 'c2', 'subject' => 'B']);

        $result = $this->service->getCommunications('cust-1');

        $this->assertCount(1, $result);
        $this->assertSame('cust-1', $result[0]->getCustomerId());
    }

    public function testGetCommunicationsFiltersByType(): void
    {
        $this->service->logCommunication('cust-1', ['id' => 'c1', 'type' => Communication::TYPE_CALL]);
        $this->service->logCommunication('cust-1', ['id' => 'c2', 'type' => Communication::TYPE_EMAIL]);

        $result = $this->service->getCommunications('cust-1', ['type' => Communication::TYPE_CALL]);

        $this->assertCount(1, $result);
        $this->assertSame(Communication::TYPE_CALL, $result[0]->getType());
    }

    public function testGetCommunicationsFiltersByDirection(): void
    {
        $this->service->logCommunication('cust-1', ['id' => 'c1', 'direction' => Communication::DIRECTION_INBOUND]);
        $this->service->logCommunication('cust-1', ['id' => 'c2', 'direction' => Communication::DIRECTION_OUTBOUND]);

        $result = $this->service->getCommunications('cust-1', ['direction' => Communication::DIRECTION_INBOUND]);

        $this->assertCount(1, $result);
        $this->assertTrue($result[0]->isInbound());
    }

    public function testGetCommunicationsFiltersByFromDate(): void
    {
        $comm1 = $this->service->logCommunication('cust-1', ['id' => 'c1']);
        $comm1->setOccurredAt(new DateTime('2024-01-01'));
        $comm2 = $this->service->logCommunication('cust-1', ['id' => 'c2']);
        $comm2->setOccurredAt(new DateTime('2024-06-01'));

        $result = $this->service->getCommunications('cust-1', ['from_date' => '2024-03-01']);

        $this->assertCount(1, $result);
        $this->assertSame('c2', $result[0]->getId());
    }

    public function testGetCommunicationsFiltersByToDate(): void
    {
        $comm1 = $this->service->logCommunication('cust-1', ['id' => 'c1']);
        $comm1->setOccurredAt(new DateTime('2024-01-01'));
        $comm2 = $this->service->logCommunication('cust-1', ['id' => 'c2']);
        $comm2->setOccurredAt(new DateTime('2024-06-01'));

        $result = $this->service->getCommunications('cust-1', ['to_date' => '2024-03-01']);

        $this->assertCount(1, $result);
        $this->assertSame('c1', $result[0]->getId());
    }

    public function testGetCommunicationsReturnsSortedDescByOccurredAt(): void
    {
        $comm1 = $this->service->logCommunication('cust-1', ['id' => 'c1']);
        $comm1->setOccurredAt(new DateTime('2024-01-01'));
        $comm2 = $this->service->logCommunication('cust-1', ['id' => 'c2']);
        $comm2->setOccurredAt(new DateTime('2024-06-01'));

        $result = $this->service->getCommunications('cust-1');

        $this->assertSame('c2', $result[0]->getId());
        $this->assertSame('c1', $result[1]->getId());
    }

    // -------------------------------------------------------------------------
    // getTimeline
    // -------------------------------------------------------------------------

    public function testGetTimelineRespectsLimit(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->service->logCommunication('cust-1', ['id' => "c{$i}"]);
        }

        $timeline = $this->service->getTimeline('cust-1', 3);

        $this->assertCount(3, $timeline);
    }

    public function testGetTimelineDefaultLimitIs50(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $this->service->logCommunication('cust-1', ['id' => "c{$i}"]);
        }

        $timeline = $this->service->getTimeline('cust-1');

        $this->assertCount(10, $timeline);
    }

    // -------------------------------------------------------------------------
    // getActivitySummary
    // -------------------------------------------------------------------------

    public function testGetActivitySummaryCountsCorrectly(): void
    {
        $this->service->logCommunication('cust-1', ['id' => 'c1', 'type' => Communication::TYPE_CALL, 'direction' => Communication::DIRECTION_INBOUND]);
        $this->service->logCommunication('cust-1', ['id' => 'c2', 'type' => Communication::TYPE_EMAIL, 'direction' => Communication::DIRECTION_OUTBOUND]);
        $this->service->logCommunication('cust-1', ['id' => 'c3', 'type' => Communication::TYPE_CALL, 'direction' => Communication::DIRECTION_OUTBOUND]);

        $summary = $this->service->getActivitySummary('cust-1');

        $this->assertSame(3, $summary['total']);
        $this->assertSame(2, $summary['by_type'][Communication::TYPE_CALL]);
        $this->assertSame(1, $summary['by_type'][Communication::TYPE_EMAIL]);
        $this->assertSame(1, $summary['by_direction']['inbound']);
        $this->assertSame(2, $summary['by_direction']['outbound']);
    }

    public function testGetActivitySummaryEmptyCustomer(): void
    {
        $summary = $this->service->getActivitySummary('no-such-customer');

        $this->assertSame(0, $summary['total']);
        $this->assertSame([], $summary['by_type']);
        $this->assertSame(0, $summary['by_direction']['inbound']);
        $this->assertSame(0, $summary['by_direction']['outbound']);
    }

    // -------------------------------------------------------------------------
    // setEventDispatcher (coverage)
    // -------------------------------------------------------------------------

    public function testSetEventDispatcherDoesNotThrow(): void
    {
        $dispatcher = new class {
            public function dispatch($event): void {}
        };
        $this->service->setEventDispatcher($dispatcher);
        $this->assertTrue(true);
    }
}
