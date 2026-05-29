<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Service;

use Ksfraser\CRM\Entity\Opportunity;
use Ksfraser\CRM\Event\OpportunityCreatedEvent;
use Ksfraser\CRM\Event\OpportunityStageChangedEvent;

class OpportunityService
{
    private array $opportunities = [];
    private $eventDispatcher = null;

    public function setEventDispatcher($dispatcher): void
    {
        $this->eventDispatcher = $dispatcher;
    }

    public function createOpportunity(string $customerId, array $data): Opportunity
    {
        $opportunity = Opportunity::fromArray($data);
        $opportunity->setId($data['id'] ?? uniqid('opp_'));
        $opportunity->setCustomerId($customerId);
        $opportunity->setCreatedAt(new \DateTime());
        $opportunity->setUpdatedAt(new \DateTime());

        $this->opportunities[$opportunity->getId()] = $opportunity;

        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(new OpportunityCreatedEvent($opportunity));
        }

        return $opportunity;
    }

    public function getOpportunity(string $id): ?Opportunity
    {
        return $this->opportunities[$id] ?? null;
    }

    public function updateOpportunity(string $id, array $data): Opportunity
    {
        $opp = $this->getOpportunity($id);
        if (!$opp) {
            throw new \RuntimeException("Opportunity not found: {$id}");
        }

        $oldStage = $opp->getStage();

        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($opp, $method)) {
                $opp->$method($value);
            }
        }
        $opp->setUpdatedAt(new \DateTime());

        if ($this->eventDispatcher && $oldStage !== $opp->getStage()) {
            $this->eventDispatcher->dispatch(
                new OpportunityStageChangedEvent($opp, $oldStage, $opp->getStage())
            );
        }

        return $opp;
    }

    public function advanceStage(string $id): Opportunity
    {
        $opp = $this->getOpportunity($id);
        if (!$opp) {
            throw new \RuntimeException("Opportunity not found: {$id}");
        }

        $oldStage = $opp->getStage();
        $opp->advanceStage();
        $opp->setUpdatedAt(new \DateTime());

        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(
                new OpportunityStageChangedEvent($opp, $oldStage, $opp->getStage())
            );
        }

        return $opp;
    }

    public function closeOpportunity(string $id, bool $won, string $reason = null): Opportunity
    {
        $opp = $this->getOpportunity($id);
        if (!$opp) {
            throw new \RuntimeException("Opportunity not found: {$id}");
        }

        $oldStage = $opp->getStage();

        if ($won) {
            $opp->closeWon($reason);
        } else {
            $opp->closeLost($reason ?? 'Unknown');
        }
        $opp->setUpdatedAt(new \DateTime());

        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(
                new OpportunityStageChangedEvent($opp, $oldStage, $opp->getStage())
            );
        }

        return $opp;
    }

    public function getOpportunitiesByCustomer(string $customerId): array
    {
        return array_filter(
            $this->opportunities,
            fn(Opportunity $o) => $o->getCustomerId() === $customerId
        );
    }

    public function getOpenOpportunities(): array
    {
        return array_filter(
            $this->opportunities,
            fn(Opportunity $o) => !$o->isClosed()
        );
    }

    public function getPipeline(string $assignedTo = null): array
    {
        $opps = $this->getOpenOpportunities();
        
        if ($assignedTo) {
            $opps = array_filter(
                $opps,
                fn(Opportunity $o) => $o->getAssignedTo() === $assignedTo
            );
        }

        $pipeline = [];
        foreach ($opps as $opp) {
            $stage = $opp->getStage();
            if (!isset($pipeline[$stage])) {
                $pipeline[$stage] = [];
            }
            $pipeline[$stage][] = $opp;
        }

        return $pipeline;
    }

    public function getForecast(float $periodDays = 90): array
    {
        $cutoff = (new \DateTime())->modify("+{$periodDays} days");
        $forecast = 0.0;
        $weighted = 0.0;

        foreach ($this->getOpenOpportunities() as $opp) {
            if ($opp->getExpectedCloseDate() && $opp->getExpectedCloseDate() <= $cutoff) {
                $forecast += $opp->getAmount();
                $weighted += $opp->calculateWeightedValue();
            }
        }

        return [
            'forecast_amount' => $forecast,
            'weighted_forecast' => $weighted,
            'period_days' => $periodDays,
        ];
    }

    public function getStageStats(): array
    {
        $stats = [];
        foreach ($this->opportunities as $opp) {
            $stage = $opp->getStage();
            if (!isset($stats[$stage])) {
                $stats[$stage] = ['count' => 0, 'amount' => 0, 'weighted' => 0];
            }
            $stats[$stage]['count']++;
            $stats[$stage]['amount'] += $opp->getAmount();
            $stats[$stage]['weighted'] += $opp->calculateWeightedValue();
        }
        return $stats;
    }
}