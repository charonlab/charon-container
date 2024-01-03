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

/**
 * @package Abyss\Container
 * @since 0.4.0
 * @license LGPL-2.1
 */
interface InvokerInterface
{
    /**
     * Call the given function.
     *
     * @param callable|non-empty-string|array{class-string, non-empty-string} $callback
     *          string - class string or function name to execute
     *          array - first element The first element is the class, and the second method if not set
     *                  then sets the default method __invoke
     * @param mixed[] $parameters
     *
     * @return mixed
     */
    public function call(callable|string|array $callback, array $parameters = []): mixed;
}
