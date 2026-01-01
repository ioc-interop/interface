<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocServices_][] interface affords a registry  of service instances,
 * factories, and aliases.
 *
 * - Directives:
 *
 *     - Implementations MUST NOT convert any `$serviceName` argument to its alias.
 *
 * @phpstan-import-type ioc_service_factory_callable from IocTypeAliases
 * @phpstan-import-type ioc_service_name_string from IocTypeAliases
 * @phpstan-import-type ioc_service_object from IocTypeAliases
 */
interface IocServices
{
    /**
     * Has a shared instance of the `$serviceName` been set?
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasServiceInstance(string $serviceName) : bool;

    /**
     * Returns the shared instance of the `$serviceName`.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] if a shared instance
     *       of the `$serviceName` is not available.
     *
     * @param ioc_service_name_string $serviceName
     * @return ioc_service_object
     */
    public function getServiceInstance(string $serviceName) : object;

    /**
     * Sets the shared instance of the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     * @param ioc_service_object $instance
     */
    public function setServiceInstance(string $serviceName, object $instance) : void;

    /**
     * Unsets the shared instance of the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function unsetServiceInstance(string $serviceName) : void;

    /**
     * Has a factory for the `$serviceName` been set?
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasServiceFactory(string $serviceName) : bool;

    /**
     * Returns the factory for the `$serviceName`.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] a factory for the
     *       `$serviceName` is not available.
     *
     * @param ioc_service_name_string $serviceName
     * @return ioc_service_factory_callable
     */
    public function getServiceFactory(string $serviceName) : callable;

    /**
     * Sets the factory for the `$serviceName`.
     *
     * - Notes:
     *
     *     - **The `callable` type allows for a wide range of implementations.**
     *       Cf. the <https://php.net/callable> documentation for more.
     *
     * @param ioc_service_name_string $serviceName
     * @param ioc_service_factory_callable $serviceFactory
     */
    public function setServiceFactory(
        string $serviceName,
        callable $serviceFactory,
    ) : void;

    /**
     * Unsets the factory for the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function unsetServiceFactory(string $serviceName) : void;

    /**
     * Has an alias for the `$serviceName` been set?
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasServiceAlias(string $serviceName) : bool;

    /**
     * Returns the alias for the `$serviceName`.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] an alias for the
     *       `$serviceName` is not available.
     *
     * @param ioc_service_name_string $serviceName
     * @return ioc_service_name_string
     */
    public function getServiceAlias(string $serviceName) : string;

    /**
     * Sets the alias for one `$serviceName` to another service.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] if the
     *       `$serviceAlias` itself is aliased.
     *
     * - Notes:
     *
     *     - **Only one level of aliasing is allowed.** An alias may not point
     *       to another alias; this is to prevent the possibillity of infinite
     *       recursion.
     *
     * @param ioc_service_name_string $serviceName
     * @param ioc_service_name_string $serviceAlias
     */
    public function setServiceAlias(string $serviceName, string $serviceAlias) : void;

    /**
     * Unsets the alias for the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function unsetServiceAlias(string $serviceName) : void;
}
