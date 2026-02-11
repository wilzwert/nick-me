<?php

namespace App\Tests\Support;

/**
 * @author Wilhelm Zwertvaegher
 */
class TestRequestParameters
{
    /**
     * @param array<string, mixed>  $parameters
     * @param array<mixed>          $files
     * @param array<string, string> $server
     */
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

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array<mixed>
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @return array<string, string>
     */
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

    public function setParameter(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function setFile(string $key, mixed $value): void
    {
        $this->files[$key] = $value;
    }

    public function setServer(string $key, string $value): void
    {
        $this->server[$key] = $value;
    }

    public function setContent(string $value): void
    {
        $this->content = $value;
    }
}
