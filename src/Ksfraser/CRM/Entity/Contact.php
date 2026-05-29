<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Entity;

class Contact
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    private ?string $id = null;
    private string $customerId = '';
    private ?string $roleId = null;
    private string $firstName = '';
    private string $lastName = '';
    private string $title = '';
    private string $department = '';
    private string $email = '';
    private string $phone = '';
    private string $mobile = '';
    private bool $isPrimary = false;
    private string $status = self::STATUS_ACTIVE;
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

    public function getRoleId(): ?string
    {
        return $this->roleId;
    }

    public function setRoleId(?string $roleId): self
    {
        $this->roleId = $roleId;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFullName(): string
    {
        return trim($this->firstName . ' ' . $this->lastName);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getMobile(): string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;
        return $this;
    }

    public function isPrimary(): bool
    {
        return $this->isPrimary;
    }

    public function setIsPrimary(bool $isPrimary): self
    {
        $this->isPrimary = $isPrimary;
        return $this;
    }

    public function setAsPrimary(): self
    {
        $this->isPrimary = true;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function activate(): self
    {
        $this->status = self::STATUS_ACTIVE;
        return $this;
    }

    public function deactivate(): self
    {
        $this->status = self::STATUS_INACTIVE;
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'role_id' => $this->roleId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'title' => $this->title,
            'department' => $this->department,
            'email' => $this->email,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'is_primary' => $this->isPrimary,
            'status' => $this->status,
            'created_at' => $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function fromArray(array $data): self
    {
        $contact = new self();
        
        if (isset($data['id'])) $contact->setId($data['id']);
        if (isset($data['customer_id'])) $contact->setCustomerId($data['customer_id']);
        if (isset($data['role_id'])) $contact->setRoleId($data['role_id']);
        if (isset($data['first_name'])) $contact->setFirstName($data['first_name']);
        if (isset($data['last_name'])) $contact->setLastName($data['last_name']);
        if (isset($data['title'])) $contact->setTitle($data['title']);
        if (isset($data['department'])) $contact->setDepartment($data['department']);
        if (isset($data['email'])) $contact->setEmail($data['email']);
        if (isset($data['phone'])) $contact->setPhone($data['phone']);
        if (isset($data['mobile'])) $contact->setMobile($data['mobile']);
        if (isset($data['is_primary'])) $contact->setIsPrimary((bool)$data['is_primary']);
        if (isset($data['status'])) $contact->setStatus($data['status']);
        
        return $contact;
    }
}