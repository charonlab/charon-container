<?php

/*
 * This file is part of the charonlab/charon-container.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Tests\Container\Performance;

use Charon\Container\Container;
use Charon\Container\ContainerInterface;

class AbstractBenchCase
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    protected ContainerInterface $container;

    public function setup(): void {
        $this->container = new Container();
    }

    public function tearDown(): void {
        unset($this->container);
    }
}
