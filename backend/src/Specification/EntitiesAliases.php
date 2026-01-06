<?php

namespace App\Specification;

/**
 * @author Wilhelm Zwertvaegher
 */
class EntitiesAliases
{
    /**
     * @var array<class-string, string>
     */
    private readonly array $aliases;

    /**
     * @param string ...$args
     */
    public function __construct(...$args)
    {
        $aliases = [];
        for ($i = 0; $i < count($args)-1; $i+=2) {
            if(!class_exists($args[$i])) {
                throw new \InvalidArgumentException("Class {$args[$i]} does not exist");
            }
            $aliases[$args[$i]] = $args[$i+1];
        }
        $this->aliases = $aliases;
    }

    public function getAlias(string $class): string
    {
        return $this->aliases[$class] ?? throw new \InvalidArgumentException("Class {$class} does not exist");
    }
}
