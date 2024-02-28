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
class MemoryUsageTest extends TestCase
{
    #[DoesNotPerformAssertions]
    public function testMemoryUsage(): void {
        $container = new Container();
        $container->set(\stdClass::class, new \stdClass());
        $container->set('factory', function (ContainerInterface $container) {
            return new Service(
                $container->get(\stdClass::class)
            );
        });
        $memoryUsage = [];
        for ($i = 0; $i < 200; $i++) {
            $container->get('factory');

            \gc_collect_cycles();
            $memoryUsage[] = \memory_get_usage();
        }

        $start = \current($memoryUsage);
        $end   = \end($memoryUsage);

        echo \sprintf('Memory increased by %s', ($end - $start) / 1024 . ' KB') . PHP_EOL;
    }
}
