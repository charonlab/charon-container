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

use Abyss\Container\Exception\ContainerException;
use Abyss\Container\Resolver\ParameterResolver;

/**
 * @package Abyss\Container
 * @since 0.4.0
 * @license LGPL-2.1
 */
final class Invoker implements InvokerInterface
{
    private ParameterResolver $resolver;

    public function __construct(
        private readonly ContainerInterface $container
    ) {
        $this->resolver = new ParameterResolver($this->container);
    }


    /**
     * @inheritDoc
     */
    public function call(callable|string|array $callback, array $parameters = []): mixed
    {
        if (\is_array($callback)) {
            // if method is not set, set default one.
            if (!isset($callback[1])) {
                $callback[1] = '__invoke';
            }

            [$instance, $method] = $callback;

            if (\is_string($instance)) {
                $instance = $this->container->get($instance);
            }

            try {
                $method = new \ReflectionMethod($instance, $method);

                return $method->invokeArgs(
                    $instance,
                    $this->resolver->resolveParameters($method, $parameters)
                );
            } catch (\ReflectionException $e) {
                throw new ContainerException($e->getMessage(), $e->getCode(), $e);
            }
        }

        if (\is_string($callback) && \is_callable($callback)) {
            $callback = $callback(...);
        }

        if ($callback instanceof \Closure) {
            try {
                $reflection = new \ReflectionFunction($callback);

                return $reflection->invokeArgs(
                    $this->resolver->resolveParameters($reflection, $parameters)
                );
            } catch (\ReflectionException $e) {
                throw new ContainerException($e->getMessage(), $e->getCode(), $e);
            }
        }

        throw new \RuntimeException("Can't resolve given callable.");
    }
}
