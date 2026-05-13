<?php

namespace Chip\Traits\Api;

use Chip\Model\CompanyStatement;
use Chip\Model\CompanyStatementList;

trait Statements
{
    /**
     * @param array<string, mixed> $filters Optional query filters
     */
    public function scheduleStatement(CompanyStatement $statement, array $filters = []): CompanyStatement
    {
        return $this->mapper->map($this->request('POST', 'company_statements/', [
            'query' => $filters,
            'json' => $statement,
        ]), new CompanyStatement());
    }

    /** @return CompanyStatementList */
    public function listStatements()
    {
        return $this->mapper->map($this->request('GET', 'company_statements/'), new CompanyStatementList());
    }

    /** @return CompanyStatement */
    public function getStatement(string $statementId)
    {
        return $this->mapper->map($this->request('GET', "company_statements/$statementId/"), new CompanyStatement());
    }

    /** @return CompanyStatement */
    public function cancelStatement(string $statementId)
    {
        return $this->mapper->map($this->request('POST', "company_statements/$statementId/cancel/"), new CompanyStatement());
    }
}
