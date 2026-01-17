<?php

namespace App\Tests\Support;

/**
 * @author Wilhelm Zwertvaegher
 */
class TestRequestParameters
{
    public function __construct(
        private readonly string $method,
        private readonly string $uri,
        private array $parameters = [],
        private array $files = [],
        private array $server = [],
        private ?string $content = null,
        private bool $changeHistory = true,
    ) {
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getServer(): array
    {
        return $this->server;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function isChangeHistory(): bool
    {
        return $this->changeHistory;
    }

    public function setParameter($key, $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function setFile(string $key, $value): void
    {
        $this->files[$key] = $value;
    }

    public function setServer(string $key, $value): void
    {
        $this->server[$key] = $value;
    }

    public function setContent(string $value): void
    {
        $this->content = $value;
    }
}
