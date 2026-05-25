<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Event;

use Ksfraser\CRM\Entity\Opportunity;
use Ksfraser\CRM\Event\OpportunityCreatedEvent;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for OpportunityCreatedEvent.
 *
 * @since 1.0.0
 */
class OpportunityCreatedEventTest extends TestCase
{
    public function testGetOpportunityReturnsInjectedOpportunity(): void
    {
        $opportunity = new Opportunity();
        $event = new OpportunityCreatedEvent($opportunity);

        $this->assertSame($opportunity, $event->getOpportunity());
    }
}
