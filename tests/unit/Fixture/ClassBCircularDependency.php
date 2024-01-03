<?php

/*
 * This file is part of the abyss/container.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE.md file for details.
 */

namespace Abyss\Test\Unit\Fixture;

class ClassBCircularDependency
{
    public function __construct(ClassACircularDependency $class)
    {
    }
}
