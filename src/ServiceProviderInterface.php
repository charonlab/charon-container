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

interface ServiceProviderInterface
{
    /**
     * Registers a services into the container.
     *
     * @param \Charon\Container\ContainerInterface $container
     *  The container to register the services into.
     *
     * @return void
     */
    public function register(ContainerInterface $container): void;
}
