<?php

/*
 * This file is part of the charonlab/charon-container.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Tests\Performance;

use Charon\Container\Container;
use Charon\Container\ContainerInterface;
use Charon\Tests\Performance\Fixture\Service;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('performance')]
#[CoversNothing]
class ContainerPerformanceTest extends TestCase
{
    private ?ContainerInterface $container = null;

    public function setUp(): void {
        $this->container = new Container();
        $this->container->set(\stdClass::class, new \stdClass());
        $this->container->factory('factory', fn (ContainerInterface $container) => $container);
        $this->container->factory('fixture', function (ContainerInterface $container): Service {
            return new Service(
                $container->get(\stdClass::class)
            );
        });
    }

    #[DoesNotPerformAssertions]
    public function testContainerGetPerformance(): void {
        $start = \microtime(true);

        for ($i = 1; $i <= 20000; $i++) {
            $this->container?->get('fixture');
        }

        echo 'Get 20000 objects in ' . (\microtime(true) - $start) . ' seconds' . PHP_EOL;
    }

    public function tearDown(): void {
        $this->container = null;
    }
}
