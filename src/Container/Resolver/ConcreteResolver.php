<?php

/*
 * This file is part of the abyss/container.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\Container\Resolver;

use Abyss\Container\Exception\NotFoundException;
use Abyss\Container\Exception\ResolveException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

use function class_exists;
use function interface_exists;

/**
 * @internal
 *
 * @package Abyss\Container\Internal\Resolver
 * @since 0.1.0
 * @license LGPL-2.1
 */
final class ConcreteResolver
{
    private ParameterResolver $parameterResolver;

    public function __construct(
        private readonly ContainerInterface $container
    ) {
        $this->parameterResolver = new ParameterResolver($this->container);
    }

    /**
     *
     * @template T
     *
     * @param class-string<T>|string $abstract
     * @param mixed[] $parameters
     *
     * @return ($abstract is class-string ? T : mixed)
     *
     * @throws ResolveException
     * @throws NotFoundException
     */
    public function resolve(string $abstract, array $parameters = []): mixed
    {
        try {
            if (!(class_exists($abstract) || interface_exists($abstract))) {
                throw new NotFoundException("Can't resolve `{$abstract}`: undefined class or binding.");
            }

            $reflector = new ReflectionClass($abstract);
        } catch (ReflectionException) {
            throw new ResolveException(sprintf(
                "Entry `%s` cannot be resolved: the class is not instantiable.",
                $abstract
            ));
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return $reflector->newInstanceWithoutConstructor();
        }


        $args = $this->parameterResolver->resolveParameters(
            $constructor,
            $parameters
        );

        return $reflector->newInstanceArgs($args);
    }
}
