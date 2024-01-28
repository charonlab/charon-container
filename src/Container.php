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

class Container implements ContainerInterface
{
    /** @var array<string, object|string|numeric> $services  */
    private array $services = [];

    /** @var array<string, bool> $keys */
    private array $keys = [];

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
}
