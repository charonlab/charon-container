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

interface ContainerInterface extends \Psr\Container\ContainerInterface, BindableContainerInterface, FactoryInterface
{
    /**
     * Returns an entry of the container by its id.
     *
     * @param string|class-string<T> $id
     *  The unique identifier for the entry.
     *
     * @return ($id is class-string ? T : array|object|scalar|null)
     *  The retrieved service.
     *
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @template T of object
     */
    public function get(string $id): array|object|bool|float|int|string|null;

    /**
     * Sets a parameter or service.
     *
     * @param string $id
     *  The unique identifier for the entry.
     * @param array|object|scalar|null $value
     *  Value the define a service or a parameter.
     *
     * @return void
     */
    public function set(string $id, array|object|bool|float|int|string|null $value): void;

    /**
     * Resolves given concrete.
     *
     * @param \Closure|string $concrete
     *  The concrete service to resolve.
     * @param array<string, array|object|scalar|null> $parameters
     *   Parameters to construct a new class.
     *
     * @return array|object|scalar|null
     */
    public function resolve(Closure|string $concrete, array $parameters = []): array|object|bool|float|int|string|null;

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
