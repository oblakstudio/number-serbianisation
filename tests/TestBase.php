<?php

declare(strict_types=1);

namespace Oblak\SrbUtils\Tests;

use PHPUnit\Framework\TestCase;

class TestBase extends TestCase
{
    protected function invokePrivateMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
