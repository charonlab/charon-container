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

use Charon\Container\Exception\BindingResolvingException;
use Charon\Container\Exception\CircularDependencyException;
use Charon\Container\Exception\DependencyException;
use Charon\Container\Exception\NotFoundException;
use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

use function array_key_exists;
use function array_pop;
use function class_exists;
use function interface_exists;
use function is_string;
use function sprintf;

class Container implements ContainerInterface
{
    /** @var array<string, array|object|scalar|null> $services */
    private array $services = [];

    /** @var array<string, bool> $keys */
    private array $keys = [];

    private array $buildStack = [];

    /** @var array{concrete: \Closure, shared: bool}[] $bindings */
    private array $bindings = [];

    /** @param array<non-empty-string, array|object|scalar|null> $services */
    public function __construct(array $services = []) {
        foreach ($services as $id => $service) {
            $this->set($id, $service);
        }
    }

    /**
     * @inheritDoc
     */
    public function bind(string $abstract, Closure|null|string $concrete, bool $shared = false): void {
        if (!$concrete instanceof Closure) {
            if ($concrete === null) {
                $concrete = $abstract;
            }

            $concrete = $this->getConcreteFactory($abstract, $concrete);
        }

        $this->bindings[$abstract] = [
            'concrete' => $concrete,
            'shared' => $shared
        ];

        if ($shared) {
            $this->services[$abstract] = $concrete;
        }
    }

    /**
     * @inheritDoc
     */
    public function shared(string $abstract, Closure|string|null $concrete = null): void {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * @inheritDoc
     */
    public function isShared(string $abstract): bool {
        return isset($this->services[$abstract]) && $this->bindings[$abstract]['shared'] === true;
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): array|object|bool|float|int|string|null {
        try {
            return $this->make($id);
        } catch (Exception $exc) {
            if ($this->has($id) || $exc instanceof CircularDependencyException) {
                throw $exc;
            }

            throw new NotFoundException($id);
        }
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool {
        return array_key_exists($id, $this->keys) || array_key_exists($id, $this->bindings);
    }

    /**
     * @inheritDoc
     */
    public function set(string $id, array|object|bool|float|int|string|null $value): void {
        $this->keys[$id] = true;
        $this->services[$id] = $value;
    }

    /**
     * @inheritDoc
     */
    public function register(ServiceProviderInterface $serviceProvider): void {
        $serviceProvider->register($this);
    }

    /**
     * @inheritDoc
     */
    public function make(Closure|string $abstract, array $parameters = []): object|int|float|string|bool|null {
        if (isset($this->services[$abstract])) {
            return $this->services[$abstract];
        }

        $concrete = $this->getConcrete($abstract);

        $instance = $abstract === $concrete || $concrete instanceof Closure
            ? $this->resolve($concrete)
            : $this->make($concrete, $parameters);

        if (is_string($abstract) && $this->isShared($abstract)) {
            $this->services[$abstract] = $instance;
        }

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Closure|string $concrete, array $parameters = []): array|object|bool|float|int|string|null {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        try {
            if (!(class_exists($concrete) || interface_exists($concrete))) {
                throw new ReflectionException("Class \"$concrete\" does not exist.");
            }

            $reflection = new ReflectionClass($concrete);
        } catch (ReflectionException $e) {
            throw new BindingResolvingException("The target [$concrete] is not exists.", 0, $e);
        }

        if (!$reflection->isInstantiable()) {
            throw new BindingResolvingException("The target [$concrete] is not instantiable.");
        }

        if (isset($this->buildStack[$concrete])) {
            throw new CircularDependencyException($concrete);
        }

        $this->buildStack[$concrete] = true;

        $constructor = $reflection->getConstructor();

        if ($constructor !== null) {
            try {
                $arguments = $this->resolveDependencies(
                    $constructor->getParameters(),
                    $parameters
                );

                return $reflection->newInstanceArgs($arguments);
            } finally {
                array_pop($this->buildStack);
            }
        }

        array_pop($this->buildStack);

        return $reflection->newInstanceWithoutConstructor();
    }

    /**
     * @param \Closure|string $abstract
     * @return \Closure|string
     */
    protected function getConcrete(Closure|string $abstract): Closure|string {
        if (isset($this->bindings[$abstract])) {
            return $this->bindings[$abstract]['concrete'];
        }

        return $abstract;
    }

    /**
     * Resolves class dependencies.
     *
     * @param \ReflectionParameter[] $reflectionParameters
     *  The class reflection parameters.
     * @param array<string, array|object|scalar|null> $parameters
     *  Parameters to construct a new class.
     *
     * @return array
     */
    private function resolveDependencies(array $reflectionParameters, array $parameters): array {
        $arguments = [];

        foreach ($reflectionParameters as $parameter) {
            /** @var \ReflectionNamedType $type */
            $type = $parameter->getType();

            if (array_key_exists($parameter->getName(), $parameters)) {
                $arguments[] = $parameters[$parameter->getName()];
            } elseif (!$type->isBuiltin()) {
                $arguments[] = $this->make($type->getName());
            } else {
                if ($parameter->isDefaultValueAvailable() || $parameter->isOptional()) {
                    $arguments[] = $this->getParameterDefaultValue($parameter);
                    continue;
                }

                throw new DependencyException(
                    sprintf(
                        'Parameter `$%s` has no value defined or guessable.',
                        $parameter->getName()
                    )
                );
            }
        }

        return $arguments;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return array|object|scalar|null
     */
    private function getParameterDefaultValue(ReflectionParameter $parameter): array|object|int|float|string|bool|null {
        try {
            return $parameter->getDefaultValue();
        } catch (ReflectionException) {
            throw new DependencyException(
                sprintf(
                    'The parameter `$%s` has no type defined or guessable. It has a default value,' .
                    "It has a default value, but the default value can't be read through Reflection",
                    $parameter->getName()
                )
            );
        }
    }

    /**
     * @param string $abstract
     *  The container alias.
     * @param \Closure|class-string|string $concrete
     *  The binding.
     *
     * @return \Closure
     */
    private function getConcreteFactory(string $abstract, Closure|string $concrete): Closure {
        return function (
            ContainerInterface $container,
            array              $parameters = []
        ) use (
            $abstract,
            $concrete
        ): array|object|bool|float|int|string|null {
            if ($abstract === $concrete) {
                return $container->resolve($concrete);
            }

            /** @var array<string, array|object|scalar|null> $parameters */
            return $container->make($concrete, $parameters);
        };
    }
}
