<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Tests\Unit\Entity;

use DateTime;
use Ksfraser\CRM\Entity\Communication;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Communication entity.
 *
 * @since 1.0.0
 */
class CommunicationTest extends TestCase
{
    // -------------------------------------------------------------------------
    // Defaults
    // -------------------------------------------------------------------------

    public function testDefaultsOnConstruction(): void
    {
        $comm = new Communication();

        $this->assertNull($comm->getId());
        $this->assertSame('', $comm->getCustomerId());
        $this->assertNull($comm->getContactId());
        $this->assertSame(Communication::TYPE_NOTE, $comm->getType());
        $this->assertSame('', $comm->getSubject());
        $this->assertNull($comm->getDescription());
        $this->assertSame(Communication::DIRECTION_OUTBOUND, $comm->getDirection());
        $this->assertNull($comm->getOutcome());
        $this->assertNull($comm->getOpportunityId());
        $this->assertSame('', $comm->getUserId());
        $this->assertInstanceOf(DateTime::class, $comm->getOccurredAt());
        $this->assertNull($comm->getCreatedAt());
    }

    // -------------------------------------------------------------------------
    // Fluent setters / getters
    // -------------------------------------------------------------------------

    public function testSetAndGetId(): void
    {
        $comm = new Communication();
        $result = $comm->setId('abc-123');
        $this->assertSame($comm, $result);
        $this->assertSame('abc-123', $comm->getId());
    }

    public function testSetAndGetCustomerId(): void
    {
        $comm = (new Communication())->setCustomerId('cust-1');
        $this->assertSame('cust-1', $comm->getCustomerId());
    }

    public function testSetAndGetContactId(): void
    {
        $comm = (new Communication())->setContactId('cont-1');
        $this->assertSame('cont-1', $comm->getContactId());
    }

    public function testSetContactIdToNull(): void
    {
        $comm = (new Communication())->setContactId('cont-1')->setContactId(null);
        $this->assertNull($comm->getContactId());
    }

    public function testSetAndGetSubject(): void
    {
        $comm = (new Communication())->setSubject('Follow-up call');
        $this->assertSame('Follow-up call', $comm->getSubject());
    }

    public function testSetAndGetDescription(): void
    {
        $comm = (new Communication())->setDescription('Discussed renewal.');
        $this->assertSame('Discussed renewal.', $comm->getDescription());
    }

    public function testSetDescriptionToNull(): void
    {
        $comm = (new Communication())->setDescription('x')->setDescription(null);
        $this->assertNull($comm->getDescription());
    }

    public function testSetAndGetOutcome(): void
    {
        $comm = (new Communication())->setOutcome('Positive');
        $this->assertSame('Positive', $comm->getOutcome());
    }

    public function testSetAndGetOpportunityId(): void
    {
        $comm = (new Communication())->setOpportunityId('opp-1');
        $this->assertSame('opp-1', $comm->getOpportunityId());
    }

    public function testSetAndGetUserId(): void
    {
        $comm = (new Communication())->setUserId('user-42');
        $this->assertSame('user-42', $comm->getUserId());
    }

    public function testSetAndGetOccurredAt(): void
    {
        $dt = new DateTime('2024-01-15 10:00:00');
        $comm = (new Communication())->setOccurredAt($dt);
        $this->assertSame($dt, $comm->getOccurredAt());
    }

    public function testSetAndGetCreatedAt(): void
    {
        $dt = new DateTime('2024-01-15 09:00:00');
        $comm = (new Communication())->setCreatedAt($dt);
        $this->assertSame($dt, $comm->getCreatedAt());
    }

    // -------------------------------------------------------------------------
    // Type validation
    // -------------------------------------------------------------------------

    /** @dataProvider validTypeProvider */
    public function testSetValidType(string $type): void
    {
        $comm = (new Communication())->setType($type);
        $this->assertSame($type, $comm->getType());
    }

    public function validTypeProvider(): array
    {
        return [
            [Communication::TYPE_CALL],
            [Communication::TYPE_MEETING],
            [Communication::TYPE_EMAIL],
            [Communication::TYPE_SMS],
            [Communication::TYPE_NOTE],
            [Communication::TYPE_LETTER],
        ];
    }

    public function testSetInvalidTypeThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new Communication())->setType('fax');
    }

    // -------------------------------------------------------------------------
    // Direction validation
    // -------------------------------------------------------------------------

    public function testSetValidDirectionInbound(): void
    {
        $comm = (new Communication())->setDirection(Communication::DIRECTION_INBOUND);
        $this->assertSame(Communication::DIRECTION_INBOUND, $comm->getDirection());
        $this->assertTrue($comm->isInbound());
        $this->assertFalse($comm->isOutbound());
    }

    public function testSetValidDirectionOutbound(): void
    {
        $comm = (new Communication())->setDirection(Communication::DIRECTION_OUTBOUND);
        $this->assertTrue($comm->isOutbound());
        $this->assertFalse($comm->isInbound());
    }

    public function testSetInvalidDirectionThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new Communication())->setDirection('sideways');
    }

    // -------------------------------------------------------------------------
    // getSummary
    // -------------------------------------------------------------------------

    public function testGetSummaryReturnsSubjectWhenNoDescription(): void
    {
        $comm = (new Communication())->setSubject('My Subject');
        $this->assertSame('My Subject', $comm->getSummary());
    }

    public function testGetSummaryReturnsDescriptionWhenShortEnough(): void
    {
        $comm = (new Communication())->setSubject('S')->setDescription('Short desc');
        $this->assertSame('Short desc', $comm->getSummary());
    }

    public function testGetSummaryTruncatesLongDescription(): void
    {
        $long = str_repeat('x', 150);
        $comm = (new Communication())->setDescription($long);
        $summary = $comm->getSummary(100);
        $this->assertSame(103, strlen($summary)); // 100 chars + '...'
        $this->assertStringEndsWith('...', $summary);
    }

    public function testGetSummaryCustomMaxLength(): void
    {
        $comm = (new Communication())->setDescription('Hello World');
        $this->assertSame('Hello...', $comm->getSummary(5));
    }

    // -------------------------------------------------------------------------
    // toArray / fromArray round-trip
    // -------------------------------------------------------------------------

    public function testToArrayContainsExpectedKeys(): void
    {
        $comm = new Communication();
        $array = $comm->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('customer_id', $array);
        $this->assertArrayHasKey('contact_id', $array);
        $this->assertArrayHasKey('type', $array);
        $this->assertArrayHasKey('subject', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('direction', $array);
        $this->assertArrayHasKey('outcome', $array);
        $this->assertArrayHasKey('opportunity_id', $array);
        $this->assertArrayHasKey('user_id', $array);
        $this->assertArrayHasKey('occurred_at', $array);
        $this->assertArrayHasKey('created_at', $array);
    }

    public function testFromArrayRoundTrip(): void
    {
        $data = [
            'id'             => 'comm-99',
            'customer_id'    => 'cust-1',
            'contact_id'     => 'cont-2',
            'type'           => Communication::TYPE_CALL,
            'subject'        => 'Initial call',
            'description'    => 'Discussed product.',
            'direction'      => Communication::DIRECTION_INBOUND,
            'outcome'        => 'Interested',
            'opportunity_id' => 'opp-5',
            'user_id'        => 'user-7',
            'occurred_at'    => '2024-03-10 14:00:00',
            'created_at'     => '2024-03-10 13:55:00',
        ];

        $comm = Communication::fromArray($data);

        $this->assertSame('comm-99', $comm->getId());
        $this->assertSame('cust-1', $comm->getCustomerId());
        $this->assertSame('cont-2', $comm->getContactId());
        $this->assertSame(Communication::TYPE_CALL, $comm->getType());
        $this->assertSame('Initial call', $comm->getSubject());
        $this->assertSame('Discussed product.', $comm->getDescription());
        $this->assertSame(Communication::DIRECTION_INBOUND, $comm->getDirection());
        $this->assertSame('Interested', $comm->getOutcome());
        $this->assertSame('opp-5', $comm->getOpportunityId());
        $this->assertSame('user-7', $comm->getUserId());
        $this->assertSame('2024-03-10 14:00:00', $comm->getOccurredAt()->format('Y-m-d H:i:s'));
        $this->assertNotNull($comm->getCreatedAt());
    }

    public function testFromArrayWithMinimalData(): void
    {
        $comm = Communication::fromArray([]);
        $this->assertInstanceOf(Communication::class, $comm);
        $this->assertNull($comm->getId());
    }
}
