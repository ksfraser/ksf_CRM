<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Event;

use Ksfraser\CRM\Entity\Opportunity;

class OpportunityStageChangedEvent
{
    private Opportunity $opportunity;
    private string $oldStage;
    private string $newStage;

    public function __construct(Opportunity $opportunity, string $oldStage, string $newStage)
    {
        $this->opportunity = $opportunity;
        $this->oldStage = $oldStage;
        $this->newStage = $newStage;
    }

    public function getOpportunity(): Opportunity
    {
        return $this->opportunity;
    }

    public function getOldStage(): string
    {
        return $this->oldStage;
    }

    public function getNewStage(): string
    {
        return $this->newStage;
    }
}