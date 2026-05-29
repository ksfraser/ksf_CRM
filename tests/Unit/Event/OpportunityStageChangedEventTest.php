<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Event;

use Ksfraser\CRM\Entity\Opportunity;
use Ksfraser\CRM\Event\OpportunityStageChangedEvent;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for OpportunityStageChangedEvent.
 *
 * @since 1.0.0
 */
class OpportunityStageChangedEventTest extends TestCase
{
    public function testGettersReturnInjectedValues(): void
    {
        $opportunity = new Opportunity();
        $event = new OpportunityStageChangedEvent(
            $opportunity,
            Opportunity::STAGE_PROSPECTING,
            Opportunity::STAGE_QUALIFICATION
        );

        $this->assertSame($opportunity, $event->getOpportunity());
        $this->assertSame(Opportunity::STAGE_PROSPECTING, $event->getOldStage());
        $this->assertSame(Opportunity::STAGE_QUALIFICATION, $event->getNewStage());
    }
}
