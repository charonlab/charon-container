<?php

/*
 * This file is part of the abyss/container.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\Container\Concrete;

/**
 * @package Abyss\Container\Concrete
 * @since 0.4.0
 * @license LGPL-2.1
 */
final class Factory extends Concrete
{
    public readonly \Closure $factory;
    public readonly bool $shared;

    public function __construct(callable $callable, bool $shared = false)
    {
        $this->factory = $callable(...);
        $this->shared = $shared;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return "Factory from function();";
    }
}
