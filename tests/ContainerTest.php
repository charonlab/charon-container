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

use Charon\Container\Container;
use Charon\Container\Exception\NotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Container::class)]
class ContainerTest extends TestCase
{
    public function testHas(): void {
        $container = new Container();
        $container->set('foo', \stdClass::class);

        self::assertTrue(
            $container->has('foo')
        );
    }

    public function testGetThrowsExceptionIfEntryNotFound(): void {
        self::expectException(NotFoundException::class);

        $container = new Container();
        $container->get('bar');
    }

    public function testSet(): void {
        $container = new Container();
        $container->set('foo', \stdClass::class);

        self::assertSame(
            \stdClass::class,
            $container->get('foo')
        );
    }
}
