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

interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * Returns an entry of the container by its name.
     *
     * @template T of object
     *
     * @param string|class-string<T> $id
     *  Entry name or a class name
     *
     * @return ($id is class-string ? T : string|object|numeric)
     *  The retrieved service.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function get(string $id): string|int|object|float;

    /**
     * @param string $id
     * @param object|string|numeric $value
     *
     * @return void
     */
    public function set(string $id, string|int|object|float$value): void;
}
