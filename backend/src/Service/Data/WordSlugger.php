<?php

namespace App\Service\Data;

use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class WordSlugger implements WordSluggerInterface
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function slug(string $str): string
    {
        return strtolower($this->slugger->slug(trim($str)));
    }
}
