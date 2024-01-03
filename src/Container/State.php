<?php

/*
 * This file is part of the abyss/container.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\Container;

use Abyss\Container\Concrete\Concrete;

/**
 * @package Abyss\Container\Internal
 * @since 0.2.0
 * @license LGPL-2.1
 */
final class State
{
    /**
     * The container's bindings.
     *
     * @var array<string, Concrete> $bindings
     */
    public array $bindings = [];

    /**
     * The container's shared instances.
     *
     * @var array<string, mixed> $instances
     */
    public array $instances = [];

    /**
     * The stack of concretions currently being built.
     *
     * @var array<string, bool> $buildStack
     */
    public array $buildStack = [];
}
