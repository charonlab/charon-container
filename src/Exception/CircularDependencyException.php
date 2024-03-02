<?php

/*
 * This file is part of the charonlab/charon-container.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Container\Exception;

use RuntimeException;

class CircularDependencyException extends RuntimeException
{
    public function __construct(string $abstract) {
        parent::__construct("Circular dependency detected while trying to resolve entry '{$abstract}'");
    }
}
