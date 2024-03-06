<?php

/*
 * This file is part of the charonlab/charon-container.
 *
 * Copyright (C) 2023-2024 Charon Lab Development Team
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Charon\Tests\Container\Unit\Stubs;

class Service
{
    public function __construct(private readonly \stdClass $stdClass) {
    }

    public function value(): \stdClass {
        return $this->stdClass;
    }
}
