<?php

/*
 * This file is part of the charonlab/charon-container.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Tests\Container\Performance\Cases;

use Charon\Tests\Container\Performance\AbstractBenchCase;
use Charon\Tests\Container\Performance\Stubs\FooBar;
use PhpBench\Attributes\AfterMethods;
use PhpBench\Attributes\Assert;
use PhpBench\Attributes\BeforeMethods;

class ContainerBench extends AbstractBenchCase
{
    #[BeforeMethods('setup')]
    #[AfterMethods('tearDown')]
    #[Assert('mode(variant.time.avg) < 5ms')]
    #[Assert('mode(variant.mem.real) < 10mb')]
    public function benchResolvePerformance(): void {
        $this->container->resolve(FooBar::class);
    }
}
