<?php

namespace App\Tests\Support\Fake;

use App\Specification\QueryBuilderInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class FakeQueryBuilder implements QueryBuilderInterface
{
    /**
     * @var list<string>
     */
    private array $where = [];
    /**
     * @var array<string, mixed>
     */
    private array $parameters = [];

    private int $firstResult = 0;

    private int $maxResults = 0;

    private int $expectedCount = 0;

    public function andWhere(string $where): QueryBuilderInterface
    {
        $this->where[] = $where;

        return $this;
    }

    public function setParameter(string $field, mixed $value): QueryBuilderInterface
    {
        $this->parameters[$field] = $value;

        return $this;
    }

    public function setFirstResult(int $firstResult): QueryBuilderInterface
    {
        $this->firstResult = $firstResult;

        return $this;
    }

    public function setMaxResults(int $maxResult): QueryBuilderInterface
    {
        $this->maxResults = $maxResult;

        return $this;
    }

    public function setExpectedCount(int $expectedCount): QueryBuilderInterface
    {
        $this->expectedCount = $expectedCount;

        return $this;
    }

    public function count(string $field): int
    {
        return $this->expectedCount;
    }

    /**
     * @return list<string>
     */
    public function getWhere(): array
    {
        return $this->where;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getFirstResult(): int
    {
        return $this->firstResult;
    }

    public function getMaxResults(): int
    {
        return $this->maxResults;
    }
}
