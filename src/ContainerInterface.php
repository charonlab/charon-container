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
     * Returns an entry of the container by its id.
     *
     * @template T of object
     *
     * @param string|class-string<T> $id
     *  The unique identifier for the entry.
     *
     * @return ($id is class-string ? T : string|int|float|object)
     *  The retrieved service.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function get(string $id): string|int|float|object;

    /**
     * Sets a parameter or service.
     *
     * @param non-empty-string $id
     *  The unique identifier for the entry.
     * @param string|int|float|object $value
     *  Value the define a service or a parameter.
     *
     * @return void
     */
    public function set(string $id, string|int|float|object $value): void;

    /**
     * Registers a service provider.
     *
     * @param \Charon\Container\ServiceProviderInterface $serviceProvider
     *  The service provider to be registered.
     *
     * @return void
     */
    public function register(ServiceProviderInterface $serviceProvider): void;
}
