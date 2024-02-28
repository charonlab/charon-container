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

interface FactoryInterface
{
    /**
     * Initializes a new instance of requested class using binding and set of parameters.
     *
     * @param non-empty-string|class-string<T> $abstract
     *  The unique identifier for the entry.
     * @param array $parameters
     *  Parameters to construct a new class
     *
     * @return ($abstract is class-string ? T : object|string|int|float)
     *
     * @throws \Charon\Container\Exception\NotInvokableException
     *
     * @template T
     */
    public function make(string $abstract, array $parameters = []): string|int|float|object;
}
