<?php

declare(strict_types=1);

namespace Chip\Resource;

use Chip\Http\ClientInterface;
use Chip\Model\CompanyStatement;
use Chip\Model\CompanyStatementList;

final class StatementsResource
{
    public function __construct(private readonly ClientInterface $client)
    {
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function schedule(CompanyStatement $statement, array $filters = []): CompanyStatement
    {
        $response = $this->client->request('POST', 'company_statements/', [
            'query' => $filters,
            'json' => $statement,
        ]);

        return CompanyStatement::fromArray((array) $response);
    }

    public function list(): CompanyStatementList
    {
        $response = $this->client->request('GET', 'company_statements/');

        return CompanyStatementList::fromArray((array) $response);
    }

    /**
     * @return \Generator<int, CompanyStatement>
     */
    public function iterate(): \Generator
    {
        $url = 'company_statements/';

        while ($url !== null) {
            $response = $this->client->request('GET', $url);
            $list = CompanyStatementList::fromArray((array) $response);

            foreach ($list->results ?? [] as $statement) {
                yield $statement;
            }

            $url = $list->next;
        }
    }

    public function get(string $statementId): CompanyStatement
    {
        $response = $this->client->request('GET', "company_statements/$statementId/");

        return CompanyStatement::fromArray((array) $response);
    }

    public function cancel(string $statementId): CompanyStatement
    {
        $response = $this->client->request('POST', "company_statements/$statementId/cancel/");

        return CompanyStatement::fromArray((array) $response);
    }
}
