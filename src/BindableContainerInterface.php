<?php

/*
 * This file is part of the charonlab/charon-container.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Container;

use Closure;

interface BindableContainerInterface
{
    /**
     * Binds a given concrete with the container.
     *
     * @param class-string|string $abstract
     *  The container alias.
     * @param \Closure|null|string $concrete
     *  The binding.
     * @param bool $shared
     *  Sets a shared binding.
     *
     * @return void
     */
    public function bind(string $abstract, Closure|null|string $concrete, bool $shared = false): void;

    /**
     * Binds a given concrete with the container as shared instance.
     *
     * @param class-string|string $abstract
     *  The container alias.
     * @param \Closure|null|string $concrete
     *  The binding.
     *
     * @return void
     */
    public function shared(string $abstract, Closure|null|string $concrete): void;

    /**
     * Check if a given abstract is a shared instance.
     *
     * @param class-string|string $abstract
     *  The container alias.
     *
     * @return bool
     *  Returns TRUE if given abstract is shared, otherwise FALSE.
     */
    public function isShared(string $abstract): bool;
}
