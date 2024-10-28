<?php

namespace Fakturoid;

use Fakturoid\Auth\AuthProvider;
use Fakturoid\Auth\CredentialCallback;
use Fakturoid\Auth\Credentials;
use Fakturoid\Enum\AuthTypeEnum;
use Fakturoid\Exception\AuthorizationFailedException;
use Fakturoid\Provider\AccountProvider;
use Fakturoid\Provider\BankAccountsProvider;
use Fakturoid\Provider\EventsProvider;
use Fakturoid\Provider\ExpensesProvider;
use Fakturoid\Provider\GeneratorsProvider;
use Fakturoid\Provider\InboxFilesProvider;
use Fakturoid\Provider\InventoryItemsProvider;
use Fakturoid\Provider\InventoryMovesProvider;
use Fakturoid\Provider\InvoicesProvider;
use Fakturoid\Provider\NumberFormatsProvider;
use Fakturoid\Provider\RecurringGeneratorsProvider;
use Fakturoid\Provider\SubjectsProvider;
use Fakturoid\Provider\TodosProvider;
use Fakturoid\Provider\UsersProvider;
use Fakturoid\Provider\WebhooksProvider;
use Psr\Http\Client\ClientInterface;

class FakturoidManager
{
    /** @var AuthProvider */
    private $authProvider;

    /** @var Dispatcher */
    private $dispatcher;

    /** @var AccountProvider */
    private $accountProvider;

    /** @var BankAccountsProvider */
    private $bankAccountsProvider;

    /** @var EventsProvider */
    private $eventsProvider;

    /** @var ExpensesProvider */
    private $expensesProvider;

    /** @var GeneratorsProvider */
    private $generatorsProvider;

    /** @var InboxFilesProvider */
    private $inboxFilesProvider;

    /** @var InventoryItemsProvider */
    private $inventoryItemsProvider;

    /** @var InventoryMovesProvider  */
    private $inventoryMovesProvider;

    /** @var InvoicesProvider */
    private $invoicesProvider;

    /** @var NumberFormatsProvider */
    private $numberFormatsProvider;

    /** @var RecurringGeneratorsProvider */
    private $recurringGeneratorsProvider;

    /** @var SubjectsProvider */
    private $subjectsProvider;

    /** @var TodosProvider */
    private $todosProvider;

    /** @var UsersProvider */
    private $usersProvider;

    /** @var WebhooksProvider */
    private $webhooksProvider;

    public function __construct(
        ClientInterface $client,
        #[\SensitiveParameter] string $clientId,
        #[\SensitiveParameter] string $clientSecret,
        string $userAgent,
        ?string $accountSlug = null,
        ?string $redirectUri = null
    ) {
        $this->authProvider = new AuthProvider($clientId, $clientSecret, $redirectUri, $client);
        $this->dispatcher = new Dispatcher($userAgent, $this->authProvider, $client, $accountSlug);

        $this->accountProvider = new AccountProvider($this->dispatcher);
        $this->bankAccountsProvider = new BankAccountsProvider($this->dispatcher);
        $this->eventsProvider = new EventsProvider($this->dispatcher);
        $this->expensesProvider = new ExpensesProvider($this->dispatcher);
        $this->generatorsProvider = new GeneratorsProvider($this->dispatcher);
        $this->inboxFilesProvider = new InboxFilesProvider($this->dispatcher);
        $this->inventoryItemsProvider = new InventoryItemsProvider($this->dispatcher);
        $this->inventoryMovesProvider = new InventoryMovesProvider($this->dispatcher);
        $this->invoicesProvider = new InvoicesProvider($this->dispatcher);
        $this->numberFormatsProvider = new NumberFormatsProvider($this->dispatcher);
        $this->recurringGeneratorsProvider = new RecurringGeneratorsProvider($this->dispatcher);
        $this->subjectsProvider = new SubjectsProvider($this->dispatcher);
        $this->todosProvider = new TodosProvider($this->dispatcher);
        $this->usersProvider = new UsersProvider($this->dispatcher);
        $this->webhooksProvider = new WebhooksProvider($this->dispatcher);
    }

    public function setAccountSlug(string $companySlug): void
    {
        $this->dispatcher->setAccountSlug($companySlug);
    }

    public function getAuthProvider(): AuthProvider
    {
        return $this->authProvider;
    }

    public function getAuthenticationUrl(): string
    {
        return $this->authProvider->getAuthenticationUrl();
    }

    /**
     * @throws AuthorizationFailedException
     */
    public function requestCredentials(string $code): void
    {
        $this->authProvider->loadCode($code);
        $this->authProvider->auth(AuthTypeEnum::AUTHORIZATION_CODE_FLOW);
    }

    public function getCredentials(): ?Credentials
    {
        return $this->authProvider->getCredentials();
    }

    public function setCredentials(Credentials $credentials): void
    {
        $this->authProvider->setCredentials($credentials);
    }

    public function setCredentialsCallback(CredentialCallback $callback): void
    {
        $this->authProvider->setCredentialsCallback($callback);
    }

    /**
     * @throws AuthorizationFailedException
     */
    public function authClientCredentials(): void
    {
        $this->authProvider->auth(AuthTypeEnum::CLIENT_CREDENTIALS_CODE_FLOW);
    }

    public function getDispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }

    public function getAccountProvider(): AccountProvider
    {
        return $this->accountProvider;
    }

    public function getBankAccountsProvider(): BankAccountsProvider
    {
        return $this->bankAccountsProvider;
    }

    public function getEventsProvider(): EventsProvider
    {
        return $this->eventsProvider;
    }

    public function getExpensesProvider(): ExpensesProvider
    {
        return $this->expensesProvider;
    }

    public function getGeneratorsProvider(): GeneratorsProvider
    {
        return $this->generatorsProvider;
    }

    public function getInboxFilesProvider(): InboxFilesProvider
    {
        return $this->inboxFilesProvider;
    }

    public function getInventoryItemsProvider(): InventoryItemsProvider
    {
        return $this->inventoryItemsProvider;
    }

    public function getInventoryMovesProvider(): InventoryMovesProvider
    {
        return $this->inventoryMovesProvider;
    }

    public function getInvoicesProvider(): InvoicesProvider
    {
        return $this->invoicesProvider;
    }

    public function getNumberFormatsProvider(): NumberFormatsProvider
    {
        return $this->numberFormatsProvider;
    }

    public function getRecurringGeneratorsProvider(): RecurringGeneratorsProvider
    {
        return $this->recurringGeneratorsProvider;
    }

    public function getSubjectsProvider(): SubjectsProvider
    {
        return $this->subjectsProvider;
    }

    public function getTodosProvider(): TodosProvider
    {
        return $this->todosProvider;
    }

    public function getUsersProvider(): UsersProvider
    {
        return $this->usersProvider;
    }

    public function getWebhooksProvider(): WebhooksProvider
    {
        return $this->webhooksProvider;
    }
}
