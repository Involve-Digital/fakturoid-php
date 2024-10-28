<?php

namespace Fakturoid\Provider;

use Fakturoid\DispatcherInterface;
use Fakturoid\Response;

final class BankAccountsProvider extends Provider
{
    /** @var DispatcherInterface */
    private $dispatcher;

    public function __construct(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function list(): Response
    {
        return $this->dispatcher->get('/accounts/{accountSlug}/bank_accounts.json');
    }
}
