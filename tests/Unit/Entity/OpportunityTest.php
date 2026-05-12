<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Entity;

use Ksfraser\CRM\Entity\Opportunity;
use PHPUnit\Framework\TestCase;

class OpportunityTest extends TestCase
{
    private Opportunity $opportunity;

    protected function setUp(): void
    {
        $this->opportunity = new Opportunity();
    }

    public function testSetAndGetCustomerId(): void
    {
        $result = $this->opportunity->setCustomerId('cust_123');
        $this->assertSame($this->opportunity, $result);
        $this->assertSame('cust_123', $this->opportunity->getCustomerId());
    }

    public function testSetAndGetTitle(): void
    {
        $result = $this->opportunity->setTitle('New Deal');
        $this->assertSame($this->opportunity, $result);
        $this->assertSame('New Deal', $this->opportunity->getTitle());
    }

    public function testSetAndGetAmount(): void
    {
        $result = $this->opportunity->setAmount(50000.00);
        $this->assertSame($this->opportunity, $result);
        $this->assertSame(50000.00, $this->opportunity->getAmount());
    }

    public function testSetAndGetStage(): void
    {
        $result = $this->opportunity->setStage(Opportunity::STAGE_NEGOTIATION);
        $this->assertSame($this->opportunity, $result);
        $this->assertSame(Opportunity::STAGE_NEGOTIATION, $this->opportunity->getStage());
    }

    public function testAdvanceStage(): void
    {
        $this->opportunity->setStage(Opportunity::STAGE_QUALIFICATION);
        $this->opportunity->advanceStage();
        $this->assertSame(Opportunity::STAGE_PROPOSAL, $this->opportunity->getStage());
    }

    public function testCloseWon(): void
    {
        $this->opportunity->setStage(Opportunity::STAGE_NEGOTIATION);
        $this->opportunity->closeWon();
        $this->assertSame(Opportunity::STAGE_CLOSED_WON, $this->opportunity->getStage());
    }

    public function testCloseLost(): void
    {
        $this->opportunity->setStage(Opportunity::STAGE_PROPOSAL);
        $this->opportunity->closeLost('Price too high');
        $this->assertSame(Opportunity::STAGE_CLOSED_LOST, $this->opportunity->getStage());
    }

    public function testToArrayReturnsAllFields(): void
    {
        $this->opportunity->setId('opp_123');
        $this->opportunity->setCustomerId('cust_456');
        $this->opportunity->setTitle('Enterprise Deal');
        $this->opportunity->setAmount(100000.00);
        $this->opportunity->setStage(Opportunity::STAGE_PROPOSAL);

        $array = $this->opportunity->toArray();

        $this->assertSame('opp_123', $array['id']);
        $this->assertSame('cust_456', $array['customer_id']);
        $this->assertSame('Enterprise Deal', $array['title']);
        $this->assertSame(100000.00, $array['amount']);
        $this->assertSame(Opportunity::STAGE_PROPOSAL, $array['stage']);
    }

    public function testFromArrayCreatesOpportunity(): void
    {
        $data = [
            'id' => 'opp_789',
            'customer_id' => 'cust_001',
            'title' => 'New Opportunity',
            'amount' => 75000.00,
            'stage' => Opportunity::STAGE_QUALIFICATION,
        ];

        $opp = Opportunity::fromArray($data);

        $this->assertSame('opp_789', $opp->getId());
        $this->assertSame('cust_001', $opp->getCustomerId());
        $this->assertSame('New Opportunity', $opp->getTitle());
        $this->assertSame(75000.00, $opp->getAmount());
        $this->assertSame(Opportunity::STAGE_QUALIFICATION, $opp->getStage());
    }
}
