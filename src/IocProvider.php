<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocProvider_][] affords provision of service instances, definitions,
 * and aliases to an [_IocServices_][] instance.
 */
interface IocProvider
{
    /**
     * Provides service instances, definitions, and aliases to the `$services`.
     *
     * - Notes:
     *
     *     - **Provision includes a wide range of activity.** The implementation
     *       can set, unset, replace, modify, etc. the instances, definitions, and
     *       aliases in the `$services`.
     */
    public function provide(IocServices $services) : void;
}
