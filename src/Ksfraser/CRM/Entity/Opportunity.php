<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Entity;

class Opportunity
{
    public const STAGE_PROSPECTING = 'prospecting';
    public const STAGE_QUALIFICATION = 'qualification';
    public const STAGE_NEEDS_ANALYSIS = 'needs_analysis';
    public const STAGE_VALUE_PROPOSITION = 'value_proposition';
    public const STAGE_DECISION = 'decision';
    public const STAGE_PROPOSAL = 'proposal';
    public const STAGE_NEGOTIATION = 'negotiation';
    public const STAGE_CLOSED_WON = 'closed_won';
    public const STAGE_CLOSED_LOST = 'closed_lost';

    private ?string $id = null;
    private string $customerId = '';
    private string $name = '';
    private float $amount = 0.0;
    private int $probability = 0;
    private string $stage = self::STAGE_PROSPECTING;
    private ?\DateTime $expectedCloseDate = null;
    private ?string $leadSource = null;
    private ?string $campaignId = null;
    private string $assignedTo = '';
    private ?\DateTime $closedDate = null;
    private ?string $closedReason = null;
    private ?\DateTime $createdAt = null;
    private ?\DateTime $updatedAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getProbability(): int
    {
        return $this->probability;
    }

    public function setProbability(int $probability): self
    {
        if ($probability < 0 || $probability > 100) {
            throw new \InvalidArgumentException('Probability must be between 0 and 100');
        }
        $this->probability = $probability;
        return $this;
    }

    public function getStage(): string
    {
        return $this->stage;
    }

    public function setStage(string $stage): self
    {
        $this->stage = $stage;
        return $this;
    }

    public function getExpectedCloseDate(): ?\DateTime
    {
        return $this->expectedCloseDate;
    }

    public function setExpectedCloseDate(?\DateTime $expectedCloseDate): self
    {
        $this->expectedCloseDate = $expectedCloseDate;
        return $this;
    }

    public function getLeadSource(): ?string
    {
        return $this->leadSource;
    }

    public function setLeadSource(?string $leadSource): self
    {
        $this->leadSource = $leadSource;
        return $this;
    }

    public function getCampaignId(): ?string
    {
        return $this->campaignId;
    }

    public function setCampaignId(?string $campaignId): self
    {
        $this->campaignId = $campaignId;
        return $this;
    }

    public function getAssignedTo(): string
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(string $assignedTo): self
    {
        $this->assignedTo = $assignedTo;
        return $this;
    }

    public function getClosedDate(): ?\DateTime
    {
        return $this->closedDate;
    }

    public function setClosedDate(?\DateTime $closedDate): self
    {
        $this->closedDate = $closedDate;
        return $this;
    }

    public function getClosedReason(): ?string
    {
        return $this->closedReason;
    }

    public function setClosedReason(?string $closedReason): self
    {
        $this->closedReason = $closedReason;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function isClosed(): bool
    {
        return in_array($this->stage, [self::STAGE_CLOSED_WON, self::STAGE_CLOSED_LOST]);
    }

    public function isWon(): bool
    {
        return $this->stage === self::STAGE_CLOSED_WON;
    }

    public function isLost(): bool
    {
        return $this->stage === self::STAGE_CLOSED_LOST;
    }

    public function calculateWeightedValue(): float
    {
        return $this->amount * ($this->probability / 100);
    }

    public function advanceStage(): self
    {
        $stages = [
            self::STAGE_PROSPECTING => self::STAGE_QUALIFICATION,
            self::STAGE_QUALIFICATION => self::STAGE_NEEDS_ANALYSIS,
            self::STAGE_NEEDS_ANALYSIS => self::STAGE_VALUE_PROPOSITION,
            self::STAGE_VALUE_PROPOSITION => self::STAGE_DECISION,
            self::STAGE_DECISION => self::STAGE_PROPOSAL,
            self::STAGE_PROPOSAL => self::STAGE_NEGOTIATION,
            self::STAGE_NEGOTIATION => self::STAGE_CLOSED_WON,
        ];

        if (isset($stages[$this->stage])) {
            $this->stage = $stages[$this->stage];
        }

        return $this;
    }

    public function closeWon(string $reason = null): self
    {
        $this->stage = self::STAGE_CLOSED_WON;
        $this->closedDate = new \DateTime();
        $this->closedReason = $reason;
        $this->probability = 100;
        return $this;
    }

    public function closeLost(string $reason): self
    {
        $this->stage = self::STAGE_CLOSED_LOST;
        $this->closedDate = new \DateTime();
        $this->closedReason = $reason;
        $this->probability = 0;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'name' => $this->name,
            'amount' => $this->amount,
            'probability' => $this->probability,
            'stage' => $this->stage,
            'expected_close_date' => $this->expectedCloseDate ? $this->expectedCloseDate->format('Y-m-d') : null,
            'lead_source' => $this->leadSource,
            'campaign_id' => $this->campaignId,
            'assigned_to' => $this->assignedTo,
            'closed_date' => $this->closedDate ? $this->closedDate->format('Y-m-d') : null,
            'closed_reason' => $this->closedReason,
            'created_at' => $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function fromArray(array $data): self
    {
        $opp = new self();
        
        if (isset($data['id'])) $opp->setId($data['id']);
        if (isset($data['customer_id'])) $opp->setCustomerId($data['customer_id']);
        if (isset($data['name'])) $opp->setName($data['name']);
        if (isset($data['amount'])) $opp->setAmount((float)$data['amount']);
        if (isset($data['probability'])) $opp->setProbability((int)$data['probability']);
        if (isset($data['stage'])) $opp->setStage($data['stage']);
        if (isset($data['expected_close_date'])) $opp->setExpectedCloseDate(new \DateTime($data['expected_close_date']));
        if (isset($data['lead_source'])) $opp->setLeadSource($data['lead_source']);
        if (isset($data['campaign_id'])) $opp->setCampaignId($data['campaign_id']);
        if (isset($data['assigned_to'])) $opp->setAssignedTo($data['assigned_to']);
        if (isset($data['closed_date'])) $opp->setClosedDate(new \DateTime($data['closed_date']));
        if (isset($data['closed_reason'])) $opp->setClosedReason($data['closed_reason']);
        
        return $opp;
    }
}