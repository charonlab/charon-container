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
use Charon\Container\Exception\NotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
#[CoversClass(Container::class)]
class ContainerTest extends TestCase
{
    public function testHasReturnTrueIfServiceExists(): void {
        $container = new Container();
        $container->set(\stdClass::class, new \stdClass());

        self::assertTrue($container->has(\stdClass::class));
    }

    public function testSharedService(): void {
        $class = new \stdClass();

        $container = new Container();
        $container->set(\stdClass::class, $class);

        self::assertSame($class, $container->get(\stdClass::class));
    }

    public function testGetThrowsExceptionIfServiceNotFound(): void {
        self::expectException(NotFoundException::class);

        $container = new Container();
        $container->get(\stdClass::class);
    }

    public function testServicesShouldBeDifferent(): void {
        $container = new Container();
        $container->set(\stdClass::class, fn () => new \stdClass());

        $firstService = $container->get(\stdClass::class);
        self::assertInstanceOf(\stdClass::class, $firstService);

        $secondService = $container->get(\stdClass::class);
        self::assertInstanceOf(\stdClass::class, $secondService);

        self::assertNotSame($firstService, $secondService);
    }

    public function testShouldPassContainerAsAParameter(): void {
        $container = new Container();
        $container->set(\stdClass::class, fn(ContainerInterface $container) => $container);

        self::assertSame($container, $container->get(\stdClass::class));
    }
}
