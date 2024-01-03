<?php

/*
 * This file is part of the abyss/container.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\Container\Concrete;

/**
 * @package Abyss\Container\Concrete
 * @since 0.2.0
 * @license LGPL-2.1
 */
final class Alias extends Concrete
{
    public function __construct(
        public readonly string $value,
        public readonly bool $shared = false
    ) {
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return \sprintf("Alias to `%s`.", $this->value);
    }
}
