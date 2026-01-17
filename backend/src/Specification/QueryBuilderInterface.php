<?php

namespace App\Specification;

/**
 * @author Wilhelm Zwertvaegher
 *
 * This interface exposes some methods used to enable query configuration by the WordCriteriaService
 * It is not designed to be  "QueryBuilder" agnostic, only to provide a way to improve WordCriteriaService testability
 * by allowing us to write a fake builder to check the configuration effects on the QueryBuilder instead of how it is done
 */
interface QueryBuilderInterface
{
    public function andWhere(string $where): self;

    public function setParameter(string $field, mixed $value): self;

    public function setFirstResult(int $firstResult): self;

    public function setMaxResults(int $maxResult): self;

    public function count(string $field): int;
}
