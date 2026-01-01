<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocProvider_][] interface affords provision of service instances,
 * factories, and aliases to an [_IocServices_][] instance.
 */
interface IocProvider
{
    /**
     * Provides service instances, factories, and aliases to the `$services`.
     *
     * - Notes:
     *
     *     - **Provision includes a wide range of activity.** The implementation
     *       can set, unset, replace, etc. the instances, factories, and aliases
     *       in the `$services`.
     */
    public function provideServices(IocServices $services) : void;
}
