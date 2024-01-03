<?php

/*
 * This file is part of the abyss/container.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\Test\Unit;

use Abyss\Container\Container;
use Abyss\Container\Exception\CircularDependencyException;
use Abyss\Test\Unit\Fixture\ClassACircularDependency;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Container::class)]
class ContainerTest extends TestCase
{
    public function testMakeIfCircularDependencyThrowsException(): void
    {
        self::expectException(CircularDependencyException::class);
        self::expectExceptionMessage(
            "Circular dependency detected while trying to resolve entry " .
            "'Abyss\Test\Unit\Fixture\ClassACircularDependency'"
        );

        $container = new Container();
        $container->make(ClassACircularDependency::class);
    }
}
