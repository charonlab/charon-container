<?php

/*
 * This file is part of the charonlab/charon-container.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Tests\Container;

use Charon\Container\BindableContainerInterface;
use Charon\Container\Container;
use Charon\Container\Exception\CircularDependencyException;
use Charon\Tests\Container\Fixtures\ClassACircularDependencies;
use Charon\Tests\Container\Fixtures\SampleClass;
use Charon\Tests\Container\Fixtures\SampleInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Container::class)]
#[CoversClass(BindableContainerInterface::class)]
class BindableContainerTest extends TestCase
{
    public function testPrimitiveValueToConcreteResolution(): void {
        $container = new Container();
        $container->bind('foo', \stdClass::class);

        self::assertInstanceOf(
            \stdClass::class,
            $container->make('foo')
        );
    }

    public function testClosureResolution(): void {
        $container = new Container();
        $container->bind('foo', function () {
            return 'bar';
        });

        self::assertSame(
            'bar',
            $container->make('foo')
        );
    }

    public function testBindingAnInstanceAsShared(): void {
        $container = new Container();
        $container->shared(SampleInterface::class, SampleClass::class);

        self::assertSame(
            $container->make(SampleInterface::class),
            $container->make(SampleInterface::class)
        );
    }

    public function testCircularDependenciesThrowsException(): void {
        self::expectException(CircularDependencyException::class);

        $container = new Container();
        $container->make(ClassACircularDependencies::class);
    }
}
