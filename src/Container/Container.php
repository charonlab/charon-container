<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of EntityManager
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace Nulldark\Container;

use Closure;
use Exception;
use Nulldark\Container\Exception\CircularDependencyException;
use Nulldark\Container\Exception\NotFoundException;
use Nulldark\Container\Resolver\ConcreteResolver;
use TypeError;

/**
 * @author Dominik Szamburski
 * @package Container
 * @license LGPL-2.1
 * @version 0.1.0
 */
class Container implements ContainerInterface
{
    /** @var ContainerInterface|null $instance */
    private static ?ContainerInterface $instance;
    private array $bindings = [];
    private array $buildStack = [];
    private array $instances = [];

    public function __construct()
    {
        $this->set(self::class, $this);
        $this->bind(ContainerInterface::class, self::class);
    }

    /**
     * @inheritDoc
     */
    public function set(string $abstract, mixed $instance): mixed
    {
        return $this->instances[$abstract] = $instance;
    }

    /**
     * @inheritDoc
     */
    public function bind(string $abstract, Closure|string|null $concrete = null, bool $shared = false): void
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }

        if (!$concrete instanceof Closure) {
            if (!is_string($concrete)) {
                throw new TypeError('Argument #2 ($concrete) must be of type Closure|string|null');
            }

            $concrete = function (ContainerInterface $container, array $parameters = []) use ($abstract, $concrete) {
                if ($abstract === $concrete) {
                    return $container->build($concrete);
                }
                return $container->make($concrete, $parameters);
            };
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }

    /**
     * @inheritDoc
     */
    public function build(string|Closure $concrete, array $parameters = []): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        if (isset($this->buildStack[$concrete])) {
            throw new CircularDependencyException(
                "Circular dependency detected while trying to resolve entry '{$concrete}'"
            );
        }

        $this->buildStack[$concrete] = true;

        try {
            $object = (new ConcreteResolver($this))->resolve($concrete, $parameters);
        } finally {
            array_pop($this->buildStack);
        }

        return $object;
    }

    /**
     * @inheritDoc
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->getConcrete($abstract);

        $object = ($concrete === $abstract || $concrete instanceof Closure)
            ? $this->build($concrete, $parameters)
            : $this->make($abstract);

        if ($this->isShared($abstract)) {
            $this->instances[$abstract] = $object;
        }

        return $object;
    }

    /**
     * @param string|callable $abstract
     * @return mixed
     */
    private function getConcrete(string|callable $abstract): mixed
    {
        return is_string($abstract) && isset($this->bindings[$abstract])
            ? $this->bindings[$abstract]['concrete']
            : $abstract;
    }

    /**
     * @inheritDoc
     */
    public function isShared(string $abstract): bool
    {
        return isset($this->instances[$abstract]) || (
                isset($this->bindings[$abstract]) && $this->bindings[$abstract]['shared'] === true
            );
    }

    /**
     * Get current container instance.
     *
     * @return ContainerInterface
     */
    public static function getInstance(): ContainerInterface
    {
        if (!isset(self::$instance)) {
            self::$instance = new Container();
        }

        return self::$instance;
    }

    /**
     * Set a new instance of container.
     *
     * @param ContainerInterface|null $instance
     * @return ContainerInterface|null
     */
    public static function setInstance(ContainerInterface $instance = null): ?ContainerInterface
    {
        return self::$instance = $instance;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): mixed
    {
        try {
            return $this->make($id);
        } catch (Exception $e) {
            if ($this->has($id) || $e instanceof CircularDependencyException) {
                throw $e;
            }

            throw new NotFoundException("Entry '$id' not found", $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->instances) || array_key_exists($id, $this->bindings);
    }

    /**
     * @inheritDoc
     */
    public function singleton(string $abstract, Closure|string|null $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }
}
