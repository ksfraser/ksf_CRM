<?php
/**
 * FrontAccounting CRM Module Exceptions
 *
 * Custom exception classes for CRM functionality.
 *
 * @package FA\Modules\CRM
 * @version 1.0.0
 * @author FrontAccounting Team
 * @license GPL-3.0
 */

namespace FA\Modules\CRM;

/**
 * Base CRM Exception
 */
class CRMException extends \Exception
{
    protected string $debtorNo;
    protected array $context;

    public function __construct(string $message, string $debtorNo = '', array $context = [], int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->debtorNo = $debtorNo;
        $this->context = $context;
    }

    public function getDebtorNo(): string
    {
        return $this->debtorNo;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getContextValue(string $key)
    {
        return $this->context[$key] ?? null;
    }
}

/**
 * CRM Customer Not Found Exception
 */
class CRMCustomerNotFoundException extends CRMException
{
    public function __construct(string $debtorNo, array $context = [])
    {
        parent::__construct("CRM customer not found: {$debtorNo}", $debtorNo, $context);
    }
}

/**
 * CRM Customer Already Exists Exception
 */
class CRMCustomerAlreadyExistsException extends CRMException
{
    public function __construct(string $debtorNo, array $context = [])
    {
        parent::__construct("CRM customer already exists: {$debtorNo}", $debtorNo, $context);
    }
}

/**
 * CRM Customer Validation Exception
 */
class CRMCustomerValidationException extends CRMException
{
    private array $validationErrors;

    public function __construct(string $message, string $debtorNo = '', array $validationErrors = [], array $context = [])
    {
        parent::__construct($message, $debtorNo, $context);
        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}

/**
 * CRM Contact Not Found Exception
 */
class CRMContactNotFoundException extends CRMException
{
    private int $contactId;

    public function __construct(int $contactId, string $debtorNo = '', array $context = [])
    {
        parent::__construct("CRM contact not found: {$contactId}", $debtorNo, $context);
        $this->contactId = $contactId;
    }

    public function getContactId(): int
    {
        return $this->contactId;
    }
}

/**
 * CRM Contact Validation Exception
 */
class CRMContactValidationException extends CRMException
{
    private array $validationErrors;

    public function __construct(string $message, string $debtorNo = '', array $validationErrors = [], array $context = [])
    {
        parent::__construct($message, $debtorNo, $context);
        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}

/**
 * CRM Opportunity Not Found Exception
 */
class CRMOpportunityNotFoundException extends CRMException
{
    private int $opportunityId;

    public function __construct(int $opportunityId, array $context = [])
    {
        parent::__construct("CRM opportunity not found: {$opportunityId}", '', $context);
        $this->opportunityId = $opportunityId;
    }

    public function getOpportunityId(): int
    {
        return $this->opportunityId;
    }
}

/**
 * CRM Opportunity Validation Exception
 */
class CRMOpportunityValidationException extends CRMException
{
    private array $validationErrors;

    public function __construct(string $message, string $debtorNo = '', array $validationErrors = [], array $context = [])
    {
        parent::__construct($message, $debtorNo, $context);
        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}

/**
 * CRM Opportunity Status Transition Exception
 */
class CRMOpportunityStatusTransitionException extends CRMException
{
    private int $opportunityId;
    private string $currentStatus;
    private string $targetStatus;

    public function __construct(int $opportunityId, string $currentStatus, string $targetStatus, array $context = [])
    {
        $message = "Invalid opportunity status transition from '{$currentStatus}' to '{$targetStatus}' for opportunity {$opportunityId}";
        parent::__construct($message, '', $context);
        $this->opportunityId = $opportunityId;
        $this->currentStatus = $currentStatus;
        $this->targetStatus = $targetStatus;
    }

    public function getOpportunityId(): int
    {
        return $this->opportunityId;
    }

    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    public function getTargetStatus(): string
    {
        return $this->targetStatus;
    }
}

/**
 * CRM Communication Not Found Exception
 */
class CRMCommunicationNotFoundException extends CRMException
{
    private int $communicationId;

    public function __construct(int $communicationId, array $context = [])
    {
        parent::__construct("CRM communication not found: {$communicationId}", '', $context);
        $this->communicationId = $communicationId;
    }

    public function getCommunicationId(): int
    {
        return $this->communicationId;
    }
}

/**
 * CRM Communication Validation Exception
 */
class CRMCommunicationValidationException extends CRMException
{
    private array $validationErrors;

    public function __construct(string $message, string $debtorNo = '', array $validationErrors = [], array $context = [])
    {
        parent::__construct($message, $debtorNo, $context);
        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}

/**
 * CRM Communication Status Exception
 */
class CRMCommunicationStatusException extends CRMException
{
    private int $communicationId;
    private string $currentStatus;
    private string $targetStatus;

    public function __construct(int $communicationId, string $currentStatus, string $targetStatus, array $context = [])
    {
        $message = "Invalid communication status transition from '{$currentStatus}' to '{$targetStatus}' for communication {$communicationId}";
        parent::__construct($message, '', $context);
        $this->communicationId = $communicationId;
        $this->currentStatus = $currentStatus;
        $this->targetStatus = $targetStatus;
    }

    public function getCommunicationId(): int
    {
        return $this->communicationId;
    }

    public function getCurrentStatus(): string
    {
        return $this->currentStatus;
    }

    public function getTargetStatus(): string
    {
        return $this->targetStatus;
    }
}

/**
 * CRM Database Exception
 */
class CRMDatabaseException extends CRMException
{
    private string $query;
    private array $parameters;

    public function __construct(string $message, string $query = '', array $parameters = [], array $context = [], int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, '', $context, $code, $previous);
        $this->query = $query;
        $this->parameters = $parameters;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}

/**
 * CRM Permission Exception
 */
class CRMPermissionException extends CRMException
{
    private string $userId;
    private string $requiredPermission;

    public function __construct(string $userId, string $requiredPermission, string $debtorNo = '', array $context = [])
    {
        $message = "User '{$userId}' does not have required permission: {$requiredPermission}";
        parent::__construct($message, $debtorNo, $context);
        $this->userId = $userId;
        $this->requiredPermission = $requiredPermission;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getRequiredPermission(): string
    {
        return $this->requiredPermission;
    }
}

/**
 * CRM Configuration Exception
 */
class CRMConfigurationException extends CRMException
{
    private string $configKey;

    public function __construct(string $configKey, string $message = '', array $context = [])
    {
        $fullMessage = $message ?: "CRM configuration error for key: {$configKey}";
        parent::__construct($fullMessage, '', $context);
        $this->configKey = $configKey;
    }

    public function getConfigKey(): string
    {
        return $this->configKey;
    }
}

/**
 * CRM Integration Exception
 */
class CRMIntegrationException extends CRMException
{
    private string $integrationType;
    private string $externalId;

    public function __construct(string $integrationType, string $externalId, string $message, array $context = [])
    {
        $fullMessage = "CRM integration error [{$integrationType}:{$externalId}]: {$message}";
        parent::__construct($fullMessage, '', $context);
        $this->integrationType = $integrationType;
        $this->externalId = $externalId;
    }

    public function getIntegrationType(): string
    {
        return $this->integrationType;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }
}