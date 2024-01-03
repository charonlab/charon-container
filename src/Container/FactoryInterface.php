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

use Abyss\Container\Exception\CircularDependencyException;
use Abyss\Container\Exception\NotFoundException;

/**
 * @package Abyss\Container
 * @since 0.2.0
 * @license LGPL-2.1
 */
interface FactoryInterface
{
    /**
     * Returns an entry of the container by its name.
     *
     * @template T of object
     *
     * @param string|class-string<T> $id
     *  $id Entry name or a class name
     *
     * @return ($id is class-string ? T : mixed)
     *  The retrieved service.
     *
     * @throws \Abyss\Container\Exception\CircularDependencyException
     * @throws \Abyss\Container\Exception\NotFoundException
     */

    /**
     * Create instance of requested class using binding class aliases and set of parameters provided.
     *
     * @template T of object
     *
     * @param string|class-string<T> $abstract
     * @param mixed[] $parameters
     *
     * @return ($abstract is class-string ? T : mixed)
     *
     * @throws \Abyss\Container\Exception\CircularDependencyException
     * @throws \Abyss\Container\Exception\NotFoundException
     */
    public function make(string $abstract, array $parameters = []): mixed;
}
