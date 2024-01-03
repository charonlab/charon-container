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
use Abyss\Test\Unit\Fixture\SampleClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Container::class)]
class ContainerFactoryTest extends TestCase
{
    public function testMakeWithGivenArguments(): void
    {
        $container = new Container();

        $instance1 = $container->make(SampleClass::class, ['foo' => 10]);
        $instance2 = $container->make(SampleClass::class, ['foo' => 10]);

        self::assertEquals(SampleClass::class, $instance1::class);
        self::assertSame($instance1->foo, $instance2->foo);
    }
}
