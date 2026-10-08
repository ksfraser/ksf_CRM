<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Entity;

class Communication
{
    public const TYPE_CALL = 'call';
    public const TYPE_MEETING = 'meeting';
    public const TYPE_EMAIL = 'email';
    public const TYPE_SMS = 'sms';
    public const TYPE_NOTE = 'note';
    public const TYPE_LETTER = 'letter';

    public const DIRECTION_INBOUND = 'inbound';
    public const DIRECTION_OUTBOUND = 'outbound';

    private ?string $id = null;
    private string $customerId = '';
    private ?string $contactId = null;
    private string $type = self::TYPE_NOTE;
    private string $subject = '';
    private ?string $description = null;
    private string $direction = self::DIRECTION_OUTBOUND;
    private ?string $outcome = null;
    private ?string $opportunityId = null;
    private string $userId = '';
    private \DateTime $occurredAt;
    private ?\DateTime $createdAt = null;

    public function __construct()
    {
        $this->occurredAt = new \DateTime();
    }

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

    public function getContactId(): ?string
    {
        return $this->contactId;
    }

    public function setContactId(?string $contactId): self
    {
        $this->contactId = $contactId;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $validTypes = [
            self::TYPE_CALL,
            self::TYPE_MEETING,
            self::TYPE_EMAIL,
            self::TYPE_SMS,
            self::TYPE_NOTE,
            self::TYPE_LETTER,
        ];
        
        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException("Invalid type: {$type}");
        }
        
        $this->type = $type;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        if (!in_array($direction, [self::DIRECTION_INBOUND, self::DIRECTION_OUTBOUND])) {
            throw new \InvalidArgumentException("Invalid direction: {$direction}");
        }
        $this->direction = $direction;
        return $this;
    }

    public function isInbound(): bool
    {
        return $this->direction === self::DIRECTION_INBOUND;
    }

    public function isOutbound(): bool
    {
        return $this->direction === self::DIRECTION_OUTBOUND;
    }

    public function getOutcome(): ?string
    {
        return $this->outcome;
    }

    public function setOutcome(?string $outcome): self
    {
        $this->outcome = $outcome;
        return $this;
    }

    public function getOpportunityId(): ?string
    {
        return $this->opportunityId;
    }

    public function setOpportunityId(?string $opportunityId): self
    {
        $this->opportunityId = $opportunityId;
        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getOccurredAt(): \DateTime
    {
        return $this->occurredAt;
    }

    public function setOccurredAt(\DateTime $occurredAt): self
    {
        $this->occurredAt = $occurredAt;
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

    public function getSummary(int $maxLength = 100): string
    {
        if (!$this->description) {
            return $this->subject;
        }
        
        if (strlen($this->description) <= $maxLength) {
            return $this->description;
        }
        
        return substr($this->description, 0, $maxLength) . '...';
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'contact_id' => $this->contactId,
            'type' => $this->type,
            'subject' => $this->subject,
            'description' => $this->description,
            'direction' => $this->direction,
            'outcome' => $this->outcome,
            'opportunity_id' => $this->opportunityId,
            'user_id' => $this->userId,
            'occurred_at' => $this->occurredAt->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function fromArray(array $data): self
    {
        $comm = new self();
        
        if (isset($data['id'])) $comm->setId($data['id']);
        if (isset($data['customer_id'])) $comm->setCustomerId($data['customer_id']);
        if (isset($data['contact_id'])) $comm->setContactId($data['contact_id']);
        if (isset($data['type'])) $comm->setType($data['type']);
        if (isset($data['subject'])) $comm->setSubject($data['subject']);
        if (isset($data['description'])) $comm->setDescription($data['description']);
        if (isset($data['direction'])) $comm->setDirection($data['direction']);
        if (isset($data['outcome'])) $comm->setOutcome($data['outcome']);
        if (isset($data['opportunity_id'])) $comm->setOpportunityId($data['opportunity_id']);
        if (isset($data['user_id'])) $comm->setUserId($data['user_id']);
        if (isset($data['occurred_at'])) $comm->setOccurredAt(new \DateTime($data['occurred_at']));
        if (isset($data['created_at'])) $comm->setCreatedAt(new \DateTime($data['created_at']));
        
        return $comm;
    }
}