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

interface FactoryInterface
{
    /**
     * Initializes a new instance of requested class using binding and set of parameters.
     *
     * @param \Closure|class-string<T>|string $abstract
     *  The unique identifier for the entry.
     * @param array<string, array|object|scalar|null> $parameters
     *  Parameters to construct a new class.
     *
     * @return ($abstract is class-string ? T : array|object|scalar|null)
     *
     * @throws \Charon\Container\Exception\CircularDependencyException
     * @throws \Charon\Container\Exception\NotFoundException
     *
     * @template T of object
     */
    public function make(Closure|string $abstract, array $parameters = []): array|object|bool|float|int|string|null;
}
