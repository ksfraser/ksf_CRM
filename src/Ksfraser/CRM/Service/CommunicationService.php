<?php

declare(strict_types=1);

namespace Ksfraser\CRM\Service;

use Ksfraser\CRM\Entity\Communication;

class CommunicationService
{
    private array $communications = [];
    private $eventDispatcher = null;

    public function setEventDispatcher($dispatcher): void
    {
        $this->eventDispatcher = $dispatcher;
    }

    public function logCommunication(string $customerId, array $data): Communication
    {
        $comm = Communication::fromArray($data);
        $comm->setId($data['id'] ?? uniqid('comm_'));
        $comm->setCustomerId($customerId);
        $comm->setCreatedAt(new \DateTime());

        $this->communications[$comm->getId()] = $comm;

        return $comm;
    }

    public function getCommunication(string $id): ?Communication
    {
        return $this->communications[$id] ?? null;
    }

    public function getCommunications(string $customerId, array $filters = []): array
    {
        $comms = array_filter(
            $this->communications,
            fn(Communication $c) => $c->getCustomerId() === $customerId
        );

        if (isset($filters['type'])) {
            $comms = array_filter(
                $comms,
                fn(Communication $c) => $c->getType() === $filters['type']
            );
        }

        if (isset($filters['direction'])) {
            $comms = array_filter(
                $comms,
                fn(Communication $c) => $c->getDirection() === $filters['direction']
            );
        }

        if (isset($filters['from_date'])) {
            $from = new \DateTime($filters['from_date']);
            $comms = array_filter(
                $comms,
                fn(Communication $c) => $c->getOccurredAt() >= $from
            );
        }

        if (isset($filters['to_date'])) {
            $to = new \DateTime($filters['to_date']);
            $comms = array_filter(
                $comms,
                fn(Communication $c) => $c->getOccurredAt() <= $to
            );
        }

        usort($comms, fn(Communication $a, Communication $b) => 
            $b->getOccurredAt() <=> $a->getOccurredAt()
        );

        return array_values($comms);
    }

    public function getTimeline(string $customerId, int $limit = 50): array
    {
        $comms = $this->getCommunications($customerId);
        return array_slice($comms, 0, $limit);
    }

    public function getActivitySummary(string $customerId): array
    {
        $comms = $this->getCommunications($customerId);
        
        $byType = [];
        $byDirection = ['inbound' => 0, 'outbound' => 0];
        
        foreach ($comms as $comm) {
            $type = $comm->getType();
            if (!isset($byType[$type])) {
                $byType[$type] = 0;
            }
            $byType[$type]++;
            
            $direction = $comm->getDirection();
            $byDirection[$direction]++;
        }

        return [
            'total' => count($comms),
            'by_type' => $byType,
            'by_direction' => $byDirection,
        ];
    }
}