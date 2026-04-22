<?php
/**
 * FrontAccounting CRM (Customer Relationship Management) Module
 *
 * Comprehensive customer relationship management system.
 *
 * @package FA\Modules\CRM
 * @version 1.0.0
 * @author FrontAccounting Team
 * @license GPL-3.0
 */

namespace FA\Modules\CRM;

use FA\Events\EventDispatcherInterface;
use FA\Database\DBALInterface;
use FA\Services\SalesService;
use Psr\Log\LoggerInterface;

/**
 * CRM Service
 *
 * Handles customer relationship management, contacts, opportunities, and analytics
 */
class CRMService
{
    private DBALInterface $db;
    private EventDispatcherInterface $events;
    private LoggerInterface $logger;
    private SalesService $salesService;

    public function __construct(
        DBALInterface $db,
        EventDispatcherInterface $events,
        LoggerInterface $logger,
        SalesService $salesService
    ) {
        $this->db = $db;
        $this->events = $events;
        $this->logger = $logger;
        $this->salesService = $salesService;
    }

    /**
     * Create a CRM customer profile
     *
     * @param array $customerData Customer profile data
     * @return CRMCustomer The created CRM customer
     * @throws CRMException
     */
    public function createCRMCustomer(array $customerData): CRMCustomer
    {
        // Validate required fields
        $this->validateCRMCustomerData($customerData);

        // Check if CRM profile already exists
        $existing = $this->db->fetchAssociative(
            'SELECT id FROM crm_customers WHERE debtor_no = ?',
            [$customerData['debtor_no']]
        );

        if ($existing) {
            throw new CRMException("CRM profile already exists for customer {$customerData['debtor_no']}");
        }

        // Set defaults
        $customerData['customer_since'] = $customerData['customer_since'] ?? date('Y-m-d');
        $customerData['edi_enabled'] = $customerData['edi_enabled'] ?? false;
        $customerData['marketing_opt_out'] = $customerData['marketing_opt_out'] ?? false;
        $customerData['preferred_contact_method'] = $customerData['preferred_contact_method'] ?? 'email';
        $customerData['credit_rating'] = $customerData['credit_rating'] ?? 'good';
        $customerData['payment_reliability'] = $customerData['payment_reliability'] ?? 100.00;
        $customerData['created_at'] = date('Y-m-d H:i:s');
        $customerData['updated_at'] = date('Y-m-d H:i:s');

        try {
            $this->db->insert('crm_customers', $customerData);

            $customer = new CRMCustomer($customerData);

            $this->events->dispatch(new CRMCustomerCreatedEvent($customer));
            $this->logger->info('CRM customer profile created', ['debtor_no' => $customerData['debtor_no']]);

            return $customer;

        } catch (\Exception $e) {
            $this->logger->error('Failed to create CRM customer profile', [
                'error' => $e->getMessage(),
                'debtor_no' => $customerData['debtor_no']
            ]);
            throw new CRMException('Failed to create CRM customer profile: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get CRM customer profile
     *
     * @param string $debtorNo Customer debtor number
     * @return CRMCustomer The CRM customer profile
     * @throws CRMCustomerNotFoundException
     */
    public function getCRMCustomer(string $debtorNo): CRMCustomer
    {
        $data = $this->db->fetchAssociative(
            'SELECT * FROM crm_customers WHERE debtor_no = ?',
            [$debtorNo]
        );

        if (!$data) {
            throw new CRMCustomerNotFoundException($debtorNo);
        }

        return new CRMCustomer($data);
    }

    /**
     * Update CRM customer profile
     *
     * @param string $debtorNo Customer debtor number
     * @param array $updateData Updated customer data
     * @return CRMCustomer The updated CRM customer
     * @throws CRMException
     */
    public function updateCRMCustomer(string $debtorNo, array $updateData): CRMCustomer
    {
        $customer = $this->getCRMCustomer($debtorNo);
        $oldData = $customer->toArray();

        // Update fields
        $updateData['updated_at'] = date('Y-m-d H:i:s');

        try {
            $this->db->update('crm_customers', $updateData, ['debtor_no' => $debtorNo]);

            // Get updated data
            $updatedCustomer = $this->getCRMCustomer($debtorNo);

            $this->events->dispatch(new CRMCustomerUpdatedEvent($updatedCustomer, $updateData));
            $this->logger->info('CRM customer profile updated', ['debtor_no' => $debtorNo]);

            return $updatedCustomer;

        } catch (\Exception $e) {
            $this->logger->error('Failed to update CRM customer profile', [
                'error' => $e->getMessage(),
                'debtor_no' => $debtorNo
            ]);
            throw new CRMException('Failed to update CRM customer profile: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Create a customer contact
     *
     * @param array $contactData Contact data
     * @return CRMContact The created contact
     * @throws CRMException
     */
    public function createContact(array $contactData): CRMContact
    {
        // Validate required fields
        $this->validateContactData($contactData);

        // Set defaults
        $contactData['is_primary'] = $contactData['is_primary'] ?? false;
        $contactData['inactive'] = $contactData['inactive'] ?? false;
        $contactData['created_at'] = date('Y-m-d H:i:s');
        $contactData['updated_at'] = date('Y-m-d H:i:s');

        try {
            $this->db->insert('crm_contacts', $contactData);

            $contact = new CRMContact($contactData);

            $this->events->dispatch(new CRMContactCreatedEvent($contact));
            $this->logger->info('Customer contact created', [
                'debtor_no' => $contactData['debtor_no'],
                'contact_id' => $contact->getId()
            ]);

            return $contact;

        } catch (\Exception $e) {
            $this->logger->error('Failed to create customer contact', [
                'error' => $e->getMessage(),
                'debtor_no' => $contactData['debtor_no']
            ]);
            throw new CRMException('Failed to create customer contact: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get customer contacts
     *
     * @param string $debtorNo Customer debtor number
     * @param bool $activeOnly Return only active contacts
     * @return CRMContact[] Array of customer contacts
     */
    public function getCustomerContacts(string $debtorNo, bool $activeOnly = true): array
    {
        $query = 'SELECT * FROM crm_contacts WHERE debtor_no = ?';
        $params = [$debtorNo];

        if ($activeOnly) {
            $query .= ' AND inactive = 0';
        }

        $query .= ' ORDER BY is_primary DESC, last_name, first_name';

        $rows = $this->db->fetchAllAssociative($query, $params);

        return array_map(fn($row) => new CRMContact($row), $rows);
    }

    /**
     * Create a sales opportunity
     *
     * @param array $opportunityData Opportunity data
     * @return CRMOpportunity The created opportunity
     * @throws CRMException
     */
    public function createOpportunity(array $opportunityData): CRMOpportunity
    {
        // Validate required fields
        $this->validateOpportunityData($opportunityData);

        // Generate opportunity ID if not provided
        if (!isset($opportunityData['id'])) {
            $opportunityData['id'] = $this->generateOpportunityId();
        }

        // Set defaults
        $opportunityData['status'] = $opportunityData['status'] ?? 'prospect';
        $opportunityData['probability'] = $opportunityData['probability'] ?? 0.00;
        $opportunityData['created_at'] = date('Y-m-d H:i:s');
        $opportunityData['updated_at'] = date('Y-m-d H:i:s');

        try {
            $this->db->insert('crm_opportunities', $opportunityData);

            $opportunity = new CRMOpportunity($opportunityData);

            $this->events->dispatch(new CRMOpportunityCreatedEvent($opportunity));
            $this->logger->info('Sales opportunity created', [
                'opportunity_id' => $opportunity->getId(),
                'customer' => $opportunityData['debtor_no']
            ]);

            return $opportunity;

        } catch (\Exception $e) {
            $this->logger->error('Failed to create sales opportunity', [
                'error' => $e->getMessage(),
                'debtor_no' => $opportunityData['debtor_no']
            ]);
            throw new CRMException('Failed to create sales opportunity: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Update opportunity status
     *
     * @param int $opportunityId Opportunity ID
     * @param string $status New status
     * @param array $additionalData Additional update data
     * @return CRMOpportunity The updated opportunity
     * @throws CRMException
     */
    public function updateOpportunityStatus(int $opportunityId, string $status, array $additionalData = []): CRMOpportunity
    {
        $opportunity = $this->getOpportunity($opportunityId);
        $oldStatus = $opportunity->getStatus();

        $updateData = array_merge($additionalData, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        try {
            $this->db->update('crm_opportunities', $updateData, ['id' => $opportunityId]);

            // Get updated opportunity
            $updatedOpportunity = $this->getOpportunity($opportunityId);

            $this->events->dispatch(new CRMOpportunityStatusUpdatedEvent($updatedOpportunity, $oldStatus));
            $this->logger->info('Opportunity status updated', [
                'opportunity_id' => $opportunityId,
                'old_status' => $oldStatus,
                'new_status' => $status
            ]);

            return $updatedOpportunity;

        } catch (\Exception $e) {
            $this->logger->error('Failed to update opportunity status', [
                'error' => $e->getMessage(),
                'opportunity_id' => $opportunityId
            ]);
            throw new CRMException('Failed to update opportunity status: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get opportunity by ID
     *
     * @param int $opportunityId Opportunity ID
     * @return CRMOpportunity The opportunity
     * @throws CRMOpportunityNotFoundException
     */
    public function getOpportunity(int $opportunityId): CRMOpportunity
    {
        $data = $this->db->fetchAssociative(
            'SELECT * FROM crm_opportunities WHERE id = ?',
            [$opportunityId]
        );

        if (!$data) {
            throw new CRMOpportunityNotFoundException($opportunityId);
        }

        return new CRMOpportunity($data);
    }

    /**
     * Record customer communication
     *
     * @param array $communicationData Communication data
     * @return CRMCommunication The recorded communication
     * @throws CRMException
     */
    public function recordCommunication(array $communicationData): CRMCommunication
    {
        // Validate required fields
        $this->validateCommunicationData($communicationData);

        // Set defaults
        $communicationData['direction'] = $communicationData['direction'] ?? 'outbound';
        $communicationData['status'] = $communicationData['status'] ?? 'completed';
        $communicationData['priority'] = $communicationData['priority'] ?? 'medium';
        $communicationData['follow_up_required'] = $communicationData['follow_up_required'] ?? false;
        $communicationData['completed_date'] = $communicationData['completed_date'] ?? date('Y-m-d H:i:s');
        $communicationData['created_at'] = date('Y-m-d H:i:s');
        $communicationData['updated_at'] = date('Y-m-d H:i:s');

        try {
            $this->db->insert('crm_communications', $communicationData);

            $communication = new CRMCommunication($communicationData);

            $this->events->dispatch(new CRMCommunicationRecordedEvent($communication));
            $this->logger->info('Customer communication recorded', [
                'communication_id' => $communication->getId(),
                'debtor_no' => $communicationData['debtor_no'],
                'type' => $communicationData['communication_type']
            ]);

            // Update customer's last communication date
            if (isset($communicationData['debtor_no'])) {
                $this->updateCustomerLastCommunication($communicationData['debtor_no']);
            }

            return $communication;

        } catch (\Exception $e) {
            $this->logger->error('Failed to record customer communication', [
                'error' => $e->getMessage(),
                'debtor_no' => $communicationData['debtor_no']
            ]);
            throw new CRMException('Failed to record customer communication: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Get customer communications
     *
     * @param string $debtorNo Customer debtor number
     * @param array $filters Additional filters
     * @return CRMCommunication[] Array of communications
     */
    public function getCustomerCommunications(string $debtorNo, array $filters = []): array
    {
        $query = 'SELECT * FROM crm_communications WHERE debtor_no = ?';
        $params = [$debtorNo];

        if (isset($filters['type'])) {
            $query .= ' AND communication_type = ?';
            $params[] = $filters['type'];
        }

        if (isset($filters['from_date'])) {
            $query .= ' AND completed_date >= ?';
            $params[] = $filters['from_date'];
        }

        if (isset($filters['to_date'])) {
            $query .= ' AND completed_date <= ?';
            $params[] = $filters['to_date'];
        }

        $query .= ' ORDER BY completed_date DESC';

        $rows = $this->db->fetchAllAssociative($query, $params);

        return array_map(fn($row) => new CRMCommunication($row), $rows);
    }

    /**
     * Get customer analytics
     *
     * @param string $debtorNo Customer debtor number
     * @param string $periodFrom Start date for analytics
     * @param string $periodTo End date for analytics
     * @return array Customer analytics data
     */
    public function getCustomerAnalytics(string $debtorNo, string $periodFrom, string $periodTo): array
    {
        // Get sales data
        $salesData = $this->db->fetchAssociative("
            SELECT
                SUM(ov_amount + ov_gst + ov_freight + ov_freight_tax + ov_discount) as total_sales,
                COUNT(*) as order_count,
                AVG(ov_amount + ov_gst + ov_freight + ov_freight_tax + ov_discount) as avg_order_value
            FROM debtor_trans
            WHERE debtor_no = ? AND type = 10 AND tran_date BETWEEN ? AND ?
        ", [$debtorNo, $periodFrom, $periodTo]);

        // Get payment data
        $paymentData = $this->db->fetchAssociative("
            SELECT
                SUM(ov_amount + ov_gst) as total_payments,
                COUNT(*) as payment_count,
                AVG(ov_amount + ov_gst) as avg_payment
            FROM debtor_trans
            WHERE debtor_no = ? AND type = 12 AND tran_date BETWEEN ? AND ?
        ", [$debtorNo, $periodFrom, $periodTo]);

        // Get outstanding balance
        $balanceData = $this->db->fetchAssociative("
            SELECT SUM(ov_amount + ov_gst) as outstanding_balance
            FROM debtor_trans
            WHERE debtor_no = ?
        ", [$debtorNo]);

        // Get communication count
        $communicationCount = $this->db->fetchOne("
            SELECT COUNT(*) FROM crm_communications
            WHERE debtor_no = ? AND completed_date BETWEEN ? AND ?
        ", [$debtorNo, $periodFrom, $periodTo]);

        // Calculate metrics
        $totalSales = (float)($salesData['total_sales'] ?? 0);
        $totalPayments = (float)($paymentData['total_payments'] ?? 0);
        $orderCount = (int)($salesData['order_count'] ?? 0);
        $outstandingBalance = (float)($balanceData['outstanding_balance'] ?? 0);

        $analytics = [
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'sales' => [
                'total' => $totalSales,
                'order_count' => $orderCount,
                'average_order_value' => $orderCount > 0 ? $totalSales / $orderCount : 0
            ],
            'payments' => [
                'total' => $totalPayments,
                'count' => (int)($paymentData['payment_count'] ?? 0),
                'average_payment' => (float)($paymentData['avg_payment'] ?? 0)
            ],
            'balance' => [
                'outstanding' => $outstandingBalance
            ],
            'communications' => [
                'count' => (int)$communicationCount
            ],
            'metrics' => [
                'payment_to_sales_ratio' => $totalSales > 0 ? ($totalPayments / $totalSales) * 100 : 0,
                'order_frequency' => $this->calculateOrderFrequency($debtorNo, $periodFrom, $periodTo)
            ]
        ];

        $this->events->dispatch(new CRMAnalyticsGeneratedEvent($debtorNo, $analytics));

        return $analytics;
    }

    /**
     * Get sales opportunities by customer
     *
     * @param string $debtorNo Customer debtor number
     * @param array $filters Additional filters
     * @return CRMOpportunity[] Array of opportunities
     */
    public function getCustomerOpportunities(string $debtorNo, array $filters = []): array
    {
        $query = 'SELECT * FROM crm_opportunities WHERE debtor_no = ?';
        $params = [$debtorNo];

        if (isset($filters['status'])) {
            $query .= ' AND status = ?';
            $params[] = $filters['status'];
        }

        $query .= ' ORDER BY expected_close_date DESC, probability DESC';

        $rows = $this->db->fetchAllAssociative($query, $params);

        return array_map(fn($row) => new CRMOpportunity($row), $rows);
    }

    /**
     * Get sales pipeline summary
     *
     * @param array $filters Filters for pipeline data
     * @return array Pipeline summary data
     */
    public function getSalesPipeline(array $filters = []): array
    {
        $query = "
            SELECT
                status,
                COUNT(*) as count,
                SUM(estimated_value * probability / 100) as weighted_value,
                SUM(estimated_value) as total_value
            FROM crm_opportunities
            WHERE 1=1
        ";
        $params = [];

        if (isset($filters['sales_person'])) {
            $query .= ' AND sales_person = ?';
            $params[] = $filters['sales_person'];
        }

        if (isset($filters['territory_id'])) {
            $query .= ' AND debtor_no IN (SELECT debtor_no FROM crm_customers WHERE territory_id = ?)';
            $params[] = $filters['territory_id'];
        }

        $query .= ' GROUP BY status ORDER BY status';

        $rows = $this->db->fetchAllAssociative($query, $params);

        $pipeline = [];
        $totals = ['count' => 0, 'weighted_value' => 0, 'total_value' => 0];

        foreach ($rows as $row) {
            $status = $row['status'];
            $pipeline[$status] = [
                'count' => (int)$row['count'],
                'weighted_value' => (float)$row['weighted_value'],
                'total_value' => (float)$row['total_value']
            ];

            $totals['count'] += (int)$row['count'];
            $totals['weighted_value'] += (float)$row['weighted_value'];
            $totals['total_value'] += (float)$row['total_value'];
        }

        return [
            'pipeline' => $pipeline,
            'totals' => $totals
        ];
    }

    /**
     * Validate CRM customer data
     *
     * @param array $data Customer data
     * @throws CRMException
     */
    private function validateCRMCustomerData(array $data): void
    {
        $required = ['debtor_no'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new CRMException("Required field '{$field}' is missing");
            }
        }

        $validContactMethods = ['email', 'phone', 'mail'];
        if (isset($data['preferred_contact_method']) && !in_array($data['preferred_contact_method'], $validContactMethods)) {
            throw new CRMException('Invalid preferred contact method');
        }

        $validRatings = ['excellent', 'good', 'fair', 'poor'];
        if (isset($data['credit_rating']) && !in_array($data['credit_rating'], $validRatings)) {
            throw new CRMException('Invalid credit rating');
        }
    }

    /**
     * Validate contact data
     *
     * @param array $data Contact data
     * @throws CRMException
     */
    private function validateContactData(array $data): void
    {
        $required = ['debtor_no', 'first_name', 'last_name'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new CRMException("Required field '{$field}' is missing");
            }
        }
    }

    /**
     * Validate opportunity data
     *
     * @param array $data Opportunity data
     * @throws CRMException
     */
    private function validateOpportunityData(array $data): void
    {
        $required = ['debtor_no', 'opportunity_name'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new CRMException("Required field '{$field}' is missing");
            }
        }

        $validStatuses = ['prospect', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
        if (isset($data['status']) && !in_array($data['status'], $validStatuses)) {
            throw new CRMException('Invalid opportunity status');
        }
    }

    /**
     * Validate communication data
     *
     * @param array $data Communication data
     * @throws CRMException
     */
    private function validateCommunicationData(array $data): void
    {
        $required = ['debtor_no', 'communication_type'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new CRMException("Required field '{$field}' is missing");
            }
        }

        $validTypes = ['call', 'meeting', 'email', 'sms', 'note', 'letter'];
        if (!in_array($data['communication_type'], $validTypes)) {
            throw new CRMException('Invalid communication type');
        }

        $validDirections = ['inbound', 'outbound', 'internal'];
        if (isset($data['direction']) && !in_array($data['direction'], $validDirections)) {
            throw new CRMException('Invalid communication direction');
        }
    }

    /**
     * Generate unique opportunity ID
     *
     * @return int Generated opportunity ID
     */
    private function generateOpportunityId(): int
    {
        // Use auto-increment, but this method could be used for custom ID generation
        return (int)$this->db->fetchOne('SELECT COALESCE(MAX(id), 0) + 1 FROM crm_opportunities');
    }

    /**
     * Update customer's last communication date
     *
     * @param string $debtorNo Customer debtor number
     */
    private function updateCustomerLastCommunication(string $debtorNo): void
    {
        try {
            $this->db->update(
                'crm_customers',
                ['last_contact_date' => date('Y-m-d'), 'updated_at' => date('Y-m-d H:i:s')],
                ['debtor_no' => $debtorNo]
            );
        } catch (\Exception $e) {
            // Log but don't throw - this is not critical
            $this->logger->warning('Failed to update customer last communication date', [
                'debtor_no' => $debtorNo,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate order frequency for a customer
     *
     * @param string $debtorNo Customer debtor number
     * @param string $periodFrom Start date
     * @param string $periodTo End date
     * @return float Orders per month
     */
    private function calculateOrderFrequency(string $debtorNo, string $periodFrom, string $periodTo): float
    {
        $orderCount = $this->db->fetchOne("
            SELECT COUNT(*) FROM debtor_trans
            WHERE debtor_no = ? AND type = 10 AND tran_date BETWEEN ? AND ?
        ", [$debtorNo, $periodFrom, $periodTo]);

        $months = $this->calculateMonthsBetween($periodFrom, $periodTo);

        return $months > 0 ? (int)$orderCount / $months : 0;
    }

    /**
     * Calculate months between two dates
     *
     * @param string $fromDate Start date
     * @param string $toDate End date
     * @return float Number of months
     */
    private function calculateMonthsBetween(string $fromDate, string $toDate): float
    {
        $from = new \DateTime($fromDate);
        $to = new \DateTime($toDate);
        $interval = $from->diff($to);

        return $interval->m + ($interval->d / 30) + ($interval->y * 12);
    }
}