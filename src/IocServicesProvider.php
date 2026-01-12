<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocServicesProvider_][] interface affords provision of service instances,
 * builders, and aliases to an [_IocServices_][] instance.
 */
interface IocServicesProvider
{
    /**
     * Provides service instances, builders, and aliases to the `$services`.
     *
     * - Notes:
     *
     *     - **Provision includes a wide range of activity.** The implementation
     *       can set, unset, replace, etc. the instances, builders, and aliases
     *       in the `$services`.
     */
    public function provideServices(IocServices $services) : void;
}
