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

interface FactoryInterface
{
    /**
     * Sets a given closure as a factory service.
     *
     * @param non-empty-string $id
     *  The unique identifier for the entry.
     * @param object $service
     *  The service definition to be used as a factory.
     *
     * @return void
     *
     * @throws \Charon\Container\Exception\NotInvokableException
     */
    public function factory(string $id, object $service): void;
}
