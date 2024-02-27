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

use Charon\Container\Exception\NotFoundException;
use Charon\Container\Exception\NotInvokableException;

class Container implements ContainerInterface, FactoryInterface
{
    /** @var array<string, object|string|numeric> $services  */
    private array $services = [];

    /** @var array<string, bool> $keys */
    private array $keys = [];

    /** @var array<non-empty-string, callable(ContainerInterface $container): string|int|float|object> $factories */
    private array $factories = [];

    /**
     * @param array<string, object|string|numeric> $services
     */
    public function __construct(array $services = []) {
        foreach ($services as $id => $service) {
            $this->set($id, $service);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): string|int|object|float {
        if (!$this->has($id)) {
            throw new NotFoundException($id);
        }

        if (isset($this->factories[$id]) && \is_callable($this->factories[$id])) {
            /** @psalm-suppress MixedReturnStatement */
            return $this->factories[$id]($this);
        }

        return $this->services[$id];
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool {
        return \array_key_exists($id, $this->keys);
    }

    /**
     * @inheritDoc
     */
    public function set(string $id, string|int|object|float $value): void {
        $this->keys[$id] = true;
        $this->services[$id] = $value;
    }

    /**
     * @inheritDoc
     */
    public function factory(string $id, object $service): void {
        if (!\method_exists($service, '__invoke')) {
            throw new NotInvokableException('Service definition is not callable.');
        }

        $this->keys[$id] = true;
        $this->factories[$id] = $service;
    }

    /**
     * @inheritDoc
     */
    public function register(ServiceProviderInterface $serviceProvider): void {
        $serviceProvider->register($this);
    }
}
