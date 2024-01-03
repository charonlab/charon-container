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

use function gettype;
use function var_export;

/**
 * @package Abyss\Container\Concrete
 * @since 0.2.0
 * @license LGPL-2.1
 */
final class Scalar extends Concrete
{
    public function __construct(
        public int|float|string|bool $value
    ) {
    }

    public function __toString(): string
    {
        return sprintf("Value (%s) %s", gettype($this->value), var_export($this->value, true));
    }
}
