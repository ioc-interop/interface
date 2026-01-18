<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocClassResolver_][] interface affords service instantiation.
 *
 * @phpstan-import-type ioc_service_name_string from IocTypeAliases
 * @phpstan-import-type ioc_service_object from IocTypeAliases
 */
interface IocClassResolver
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
     * @param string $class
     */
    public function isServiceResolvable(string $class) : bool;

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
     * @template T of object
     * @param string $class
     * @param mixed[] $args
     * @return ($class is class-string<T> ? T : object)
     */
    public function resolveService(
        IocContainer $ioc,
        string $class,
        array $args = [],
    ) : object;
}
