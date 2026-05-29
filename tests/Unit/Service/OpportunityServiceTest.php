<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Service;

use DateTime;
use Ksfraser\CRM\Entity\Opportunity;
use Ksfraser\CRM\Event\OpportunityCreatedEvent;
use Ksfraser\CRM\Event\OpportunityStageChangedEvent;
use Ksfraser\CRM\Service\OpportunityService;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for OpportunityService.
 *
 * @since 1.0.0
 */
class OpportunityServiceTest extends TestCase
{
    private OpportunityService $service;

    protected function setUp(): void
    {
        $this->service = new OpportunityService();
    }

    // -------------------------------------------------------------------------
    // Helper: simple recording dispatcher
    // -------------------------------------------------------------------------

    private function makeDispatcher(): object
    {
        return new class {
            public array $events = [];
            public function dispatch($event): void { $this->events[] = $event; }
        };
    }

    // -------------------------------------------------------------------------
    // createOpportunity
    // -------------------------------------------------------------------------

    public function testCreateOpportunityReturnsEntity(): void
    {
        $opp = $this->service->createOpportunity('cust-1', [
            'name'   => 'New Deal',
            'amount' => 5000.0,
        ]);

        $this->assertInstanceOf(Opportunity::class, $opp);
        $this->assertSame('cust-1', $opp->getCustomerId());
        $this->assertSame('New Deal', $opp->getName());
        $this->assertSame(5000.0, $opp->getAmount());
        $this->assertNotNull($opp->getId());
        $this->assertNotNull($opp->getCreatedAt());
        $this->assertNotNull($opp->getUpdatedAt());
    }

    public function testCreateOpportunityUsesProvidedId(): void
    {
        $opp = $this->service->createOpportunity('cust-1', ['id' => 'fixed-opp', 'name' => 'X']);
        $this->assertSame('fixed-opp', $opp->getId());
    }

    public function testCreateOpportunityDispatchesEvent(): void
    {
        $dispatcher = $this->makeDispatcher();
        $this->service->setEventDispatcher($dispatcher);
        $this->service->createOpportunity('cust-1', ['name' => 'Test']);

        $this->assertCount(1, $dispatcher->events);
        $this->assertInstanceOf(OpportunityCreatedEvent::class, $dispatcher->events[0]);
    }

    // -------------------------------------------------------------------------
    // getOpportunity
    // -------------------------------------------------------------------------

    public function testGetOpportunityReturnsCreated(): void
    {
        $created = $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'A']);
        $this->assertSame($created, $this->service->getOpportunity('opp-1'));
    }

    public function testGetOpportunityReturnsNullForUnknown(): void
    {
        $this->assertNull($this->service->getOpportunity('no-such'));
    }

    // -------------------------------------------------------------------------
    // updateOpportunity
    // -------------------------------------------------------------------------

    public function testUpdateOpportunityChangesFields(): void
    {
        $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'Original', 'amount' => 1000.0]);
        $updated = $this->service->updateOpportunity('opp-1', ['name' => 'Renamed', 'amount' => 2000.0]);

        $this->assertSame('Renamed', $updated->getName());
        $this->assertSame(2000.0, $updated->getAmount());
    }

    public function testUpdateOpportunityThrowsForUnknownId(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->service->updateOpportunity('no-such', ['name' => 'X']);
    }

    public function testUpdateOpportunityDispatchesStageChangedEventWhenStageChanges(): void
    {
        $dispatcher = $this->makeDispatcher();
        $this->service->setEventDispatcher($dispatcher);
        $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'T']);
        $dispatcher->events = []; // reset after create event

        $this->service->updateOpportunity('opp-1', ['stage' => Opportunity::STAGE_QUALIFICATION]);

        $this->assertCount(1, $dispatcher->events);
        $this->assertInstanceOf(OpportunityStageChangedEvent::class, $dispatcher->events[0]);
    }

    public function testUpdateOpportunityDoesNotDispatchEventWhenStageUnchanged(): void
    {
        $dispatcher = $this->makeDispatcher();
        $this->service->setEventDispatcher($dispatcher);
        $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'T', 'stage' => Opportunity::STAGE_PROSPECTING]);
        $dispatcher->events = [];

        $this->service->updateOpportunity('opp-1', ['name' => 'Same stage update']);

        $this->assertCount(0, $dispatcher->events);
    }

    // -------------------------------------------------------------------------
    // advanceStage
    // -------------------------------------------------------------------------

    public function testAdvanceStageMovesToNextStage(): void
    {
        $opp = $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'T']);
        $this->assertSame(Opportunity::STAGE_PROSPECTING, $opp->getStage());

        $this->service->advanceStage('opp-1');

        $this->assertSame(Opportunity::STAGE_QUALIFICATION, $opp->getStage());
    }

    public function testAdvanceStageThrowsForUnknownId(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->service->advanceStage('no-such');
    }

    public function testAdvanceStageDispatchesStageChangedEvent(): void
    {
        $dispatcher = $this->makeDispatcher();
        $this->service->setEventDispatcher($dispatcher);
        $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'T']);
        $dispatcher->events = [];

        $this->service->advanceStage('opp-1');

        $this->assertCount(1, $dispatcher->events);
        $this->assertInstanceOf(OpportunityStageChangedEvent::class, $dispatcher->events[0]);
    }

    // -------------------------------------------------------------------------
    // closeOpportunity
    // -------------------------------------------------------------------------

    public function testCloseOpportunityWon(): void
    {
        $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'T']);
        $opp = $this->service->closeOpportunity('opp-1', true, 'Great deal');

        $this->assertTrue($opp->isWon());
        $this->assertSame('Great deal', $opp->getClosedReason());
    }

    public function testCloseOpportunityLost(): void
    {
        $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'T']);
        $opp = $this->service->closeOpportunity('opp-1', false, 'Budget cut');

        $this->assertTrue($opp->isLost());
        $this->assertSame('Budget cut', $opp->getClosedReason());
    }

    public function testCloseOpportunityLostWithNoReasonUsesDefault(): void
    {
        $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'T']);
        $opp = $this->service->closeOpportunity('opp-1', false);

        $this->assertTrue($opp->isLost());
        $this->assertSame('Unknown', $opp->getClosedReason());
    }

    public function testCloseOpportunityThrowsForUnknownId(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->service->closeOpportunity('no-such', true);
    }

    public function testCloseOpportunityDispatchesStageChangedEvent(): void
    {
        $dispatcher = $this->makeDispatcher();
        $this->service->setEventDispatcher($dispatcher);
        $this->service->createOpportunity('cust-1', ['id' => 'opp-1', 'name' => 'T']);
        $dispatcher->events = [];

        $this->service->closeOpportunity('opp-1', true);

        $this->assertCount(1, $dispatcher->events);
        $this->assertInstanceOf(OpportunityStageChangedEvent::class, $dispatcher->events[0]);
    }

    // -------------------------------------------------------------------------
    // getOpportunitiesByCustomer
    // -------------------------------------------------------------------------

    public function testGetOpportunitiesByCustomer(): void
    {
        $this->service->createOpportunity('cust-1', ['id' => 'o1', 'name' => 'A']);
        $this->service->createOpportunity('cust-1', ['id' => 'o2', 'name' => 'B']);
        $this->service->createOpportunity('cust-2', ['id' => 'o3', 'name' => 'C']);

        $result = $this->service->getOpportunitiesByCustomer('cust-1');

        $this->assertCount(2, $result);
    }

    // -------------------------------------------------------------------------
    // getOpenOpportunities
    // -------------------------------------------------------------------------

    public function testGetOpenOpportunitiesExcludesClosed(): void
    {
        $this->service->createOpportunity('cust-1', ['id' => 'o1', 'name' => 'Open']);
        $this->service->createOpportunity('cust-1', ['id' => 'o2', 'name' => 'Closed']);
        $this->service->closeOpportunity('o2', true);

        $open = $this->service->getOpenOpportunities();

        $this->assertCount(1, $open);
    }

    // -------------------------------------------------------------------------
    // getPipeline
    // -------------------------------------------------------------------------

    public function testGetPipelineGroupsByStage(): void
    {
        $this->service->createOpportunity('cust-1', ['id' => 'o1', 'name' => 'A', 'stage' => Opportunity::STAGE_PROSPECTING]);
        $this->service->createOpportunity('cust-1', ['id' => 'o2', 'name' => 'B', 'stage' => Opportunity::STAGE_QUALIFICATION]);
        $this->service->createOpportunity('cust-1', ['id' => 'o3', 'name' => 'C', 'stage' => Opportunity::STAGE_PROSPECTING]);

        $pipeline = $this->service->getPipeline();

        $this->assertCount(2, $pipeline[Opportunity::STAGE_PROSPECTING]);
        $this->assertCount(1, $pipeline[Opportunity::STAGE_QUALIFICATION]);
    }

    public function testGetPipelineFiltersByAssignedTo(): void
    {
        $o1 = $this->service->createOpportunity('cust-1', ['id' => 'o1', 'name' => 'A']);
        $o1->setAssignedTo('alice');
        $o2 = $this->service->createOpportunity('cust-1', ['id' => 'o2', 'name' => 'B']);
        $o2->setAssignedTo('bob');

        $pipeline = $this->service->getPipeline('alice');

        $allOpps = array_merge(...array_values($pipeline));
        $this->assertCount(1, $allOpps);
    }

    // -------------------------------------------------------------------------
    // getForecast
    // -------------------------------------------------------------------------

    public function testGetForecastIncludesOpportunitiesClosingWithinPeriod(): void
    {
        $opp = $this->service->createOpportunity('cust-1', ['id' => 'o1', 'name' => 'A', 'amount' => 1000.0, 'probability' => 50]);
        $opp->setExpectedCloseDate(new DateTime('+30 days'));

        $forecast = $this->service->getForecast(90);

        $this->assertSame(1000.0, $forecast['forecast_amount']);
        $this->assertSame(500.0, $forecast['weighted_forecast']);
        $this->assertSame(90.0, $forecast['period_days']);
    }

    public function testGetForecastExcludesOpportunitiesBeyondPeriod(): void
    {
        $opp = $this->service->createOpportunity('cust-1', ['id' => 'o1', 'name' => 'A', 'amount' => 1000.0]);
        $opp->setExpectedCloseDate(new DateTime('+200 days'));

        $forecast = $this->service->getForecast(90);

        $this->assertSame(0.0, $forecast['forecast_amount']);
    }

    // -------------------------------------------------------------------------
    // getStageStats
    // -------------------------------------------------------------------------

    public function testGetStageStatsCountsAndSumsCorrectly(): void
    {
        $this->service->createOpportunity('cust-1', ['id' => 'o1', 'name' => 'A', 'amount' => 1000.0, 'probability' => 20, 'stage' => Opportunity::STAGE_PROSPECTING]);
        $this->service->createOpportunity('cust-1', ['id' => 'o2', 'name' => 'B', 'amount' => 2000.0, 'probability' => 50, 'stage' => Opportunity::STAGE_QUALIFICATION]);
        $this->service->createOpportunity('cust-1', ['id' => 'o3', 'name' => 'C', 'amount' => 500.0, 'probability' => 20, 'stage' => Opportunity::STAGE_PROSPECTING]);

        $stats = $this->service->getStageStats();

        $this->assertSame(2, $stats[Opportunity::STAGE_PROSPECTING]['count']);
        $this->assertSame(1500.0, $stats[Opportunity::STAGE_PROSPECTING]['amount']);
        $this->assertSame(1, $stats[Opportunity::STAGE_QUALIFICATION]['count']);
        $this->assertSame(1000.0, $stats[Opportunity::STAGE_QUALIFICATION]['weighted']);
    }

    // -------------------------------------------------------------------------
    // setEventDispatcher (coverage)
    // -------------------------------------------------------------------------

    public function testSetEventDispatcherDoesNotThrow(): void
    {
        $dispatcher = $this->makeDispatcher();
        $this->service->setEventDispatcher($dispatcher);
        $this->assertTrue(true);
    }
}
