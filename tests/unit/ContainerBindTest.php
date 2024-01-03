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

use Abyss\Container\Concrete\Scalar;
use Abyss\Container\Container;
use Abyss\Test\Unit\Fixture\ContainerImplementation;
use Abyss\Test\Unit\Fixture\ContainerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(Container::class)]
class ContainerBindTest extends TestCase
{
    public function testContainerCanBindAnyValue(): void
    {
        $container = new Container();
        $container->bind('foo', new Scalar('foo'));

        self::assertEquals('foo', $container->make('foo'));
    }

    public function testPrimitiveValueToConcreteResolution(): void
    {
        $container = new Container();
        $container->bind('foo', stdClass::class);

        self::assertInstanceOf(stdClass::class, $container->make('foo'));
    }

    public function testBindingAnInstanceAsShared(): void
    {
        $container = new Container();
        $container->singleton(ContainerInterface::class, ContainerImplementation::class);

        $instance1 = $container->make(ContainerInterface::class);
        $instance2 = $container->make(ContainerInterface::class);

        self::assertSame($instance1, $instance2);
    }
}
