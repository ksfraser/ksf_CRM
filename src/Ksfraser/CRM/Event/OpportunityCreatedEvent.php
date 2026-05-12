<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Event;

use Ksfraser\CRM\Entity\Opportunity;

class OpportunityCreatedEvent
{
    private Opportunity $opportunity;

    public function __construct(Opportunity $opportunity)
    {
        $this->opportunity = $opportunity;
    }

    public function getOpportunity(): Opportunity
    {
        return $this->opportunity;
    }
}