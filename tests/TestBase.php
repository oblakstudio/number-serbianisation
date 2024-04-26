<?php

declare(strict_types=1);

namespace Oblak\Intl\Tests;

use PHPUnit\Framework\TestCase;

class TestBase extends TestCase
{
    protected function invokePrivateMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($object::class);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
