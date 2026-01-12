<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocServiceResolver_][] interface affords service instantiation.
 */
interface IocServiceResolver
{
    /**
     * Is the service resolvable?
     *
     * - Notes:
     *
     *     - **TBD** Is `$serviceName` an existing and instantiable class?
     *       (Might use reflection.)
     *
     *     - **TBD** Take the name as given, do not convert to alias.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function isServiceResolvable(string $serviceName) : bool;

    /**
     * Given an [_IocContainer_][] to locate service dependencies, instantiates
     * and returns the `$serviceName` with `$serviceArgs` constructor argument
     * overrides.
     *
     * - Notes:
     *
     *     - **TBD** Throw if `! isServiceResolvable($serviceName)`.
     *
     *     - **TBD** Take the name as given, do not convert to alias.
     *
     *     - **TBD** Autowiring, attributes, defaults, etc.
     *
     * @param ioc_service_name_string $serviceName
     * @param mixed[] $serviceArgs
     * @return ioc_service_object
     */
    public function resolveService(
        IocContainer $ioc,
        string $serviceName,
        array $serviceArgs = [],
    ) : object;
}
