<?php

namespace App\Tests\Integration\UseCase;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class GetWordIT extends KernelTestCase
{

    #[Test]
    public function shouldGetWord(): void
    {
        self::expectNotToPerformAssertions();
        // TODO
    }

}
