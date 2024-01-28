<?php

/*
 * This file is part of the charonlab/charon-container.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Tests\Unit;

use Charon\Container\Container;
use Charon\Container\ContainerInterface;
use Charon\Container\Exception\NotInvokableException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[CoversClass(Container::class)]
class FactoryTest extends TestCase
{
    public function testServicesShouldBeDifferent(): void {
        $container = new Container();
        $container->factory(\stdClass::class, fn () => new \stdClass());

        $firstService = $container->get(\stdClass::class);
        self::assertInstanceOf(\stdClass::class, $firstService);

        $secondService = $container->get(\stdClass::class);
        self::assertInstanceOf(\stdClass::class, $secondService);

        self::assertNotSame($firstService, $secondService);
    }

    public function testShouldPassContainerAsAParameter(): void {
        $container = new Container();
        $container->factory(\stdClass::class, fn(ContainerInterface $container) => $container);

        self::assertSame($container, $container->get(\stdClass::class));
    }

    public function testFactoryThrowsExceptionIfDefinitionIsNotInvokable(): void {
        self::expectException(NotInvokableException::class);
        self::expectExceptionMessage('Service definition is not callable.');

        $service = new \stdClass();

        $container = new Container();
        $container->factory('test', $service);
    }
}
