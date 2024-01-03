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

use Abyss\Container\Exception\DependencyException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use ReflectionFunctionAbstract;
use ReflectionNamedType;
use ReflectionParameter;

use function array_key_exists;

/**
 * @internal
 *
 * @package Abyss\Container\Internal\Resolver
 * @since 0.1.0
 * @license LGPL-2.1
 */
final class ParameterResolver
{
    /** @var list<mixed> $stack */
    private array $stack;

    public function __construct(
        private readonly ContainerInterface $container
    ) {
        $this->stack = [];
    }

    /**
     * Resolves a parameters for given method.
     *
     * @param ReflectionFunctionAbstract|null $method
     * @param mixed[] $parameters
     *
     * @return list<mixed>
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function resolveParameters(\ReflectionFunctionAbstract $method = null, array $parameters = []): array
    {
        if ($method === null) {
            return $this->stack;
        }

        foreach ($method->getParameters() as $parameter) {
            /** @var null|ReflectionNamedType $type */
            $type = $parameter->getType();

            if (array_key_exists($parameter->getName(), $parameters)) {
                $this->stack[] = $parameters[$parameter->getName()];
            } elseif ($type !== null && !$type->isBuiltin()) {
                $this->stack[] = $this->container->get($type->getName());
            } else {
                if ($parameter->isDefaultValueAvailable() || $parameter->isOptional()) {
                    $this->stack[] = $this->getParameterDefaultValue($parameter);
                    continue;
                }

                throw new DependencyException(sprintf(
                    "Parameter `$%s` has no value defined or guessable.",
                    $parameter->getName()
                ));
            }
        }

        return $this->stack;
    }

    private function getParameterDefaultValue(ReflectionParameter $parameter): mixed
    {
        try {
            return $parameter->getDefaultValue();
        } catch (ReflectionException) {
            throw new DependencyException(sprintf(
                "The parameter `$%s` has no type defined or guessable. It has a default value," .
                "It has a default value, but the default value can't be read through Reflection",
                $parameter->getName()
            ));
        }
    }
}
