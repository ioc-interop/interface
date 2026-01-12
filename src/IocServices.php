<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocServices_][] interface affords a registry of service instances,
 * builders, and aliases.
 *
 * - Directives:
 *
 *     - Implementations MUST NOT convert any `$serviceName` argument to its
 *       alias.
 *
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
     * Has an [_IocServiceBuilder_][] for the `$serviceName` been set?
     *
     * - Notes:
     *
     *     - **TBD** May not have much meaning since getServiceBuilder() always
     *       returns an instance.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasServiceBuilder(string $serviceName) : bool;

    /**
     * Returns the [_IocServiceBuilder_][] for the `$serviceName`, instantiating
     * it if needed.
     *
     * - Notes:
     *
     *     - **TBD** Create using newServiceBuilder() and retain for later
     *       return.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function getServiceBuilder(string $serviceName) : IocServiceBuilder;

    /**
     * Returns a new [_IocServiceBuilder_][] for the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function newServiceBuilder(string $serviceName) : IocServiceBuilder;

    /**
     * Sets the [_IocServiceBuilder_][] for the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function setServiceBuilder(
        string $serviceName,
        IocServiceBuilder $serviceBuilder,
    ) : void;

    /**
     * Unsets the [_IocServiceBuilder_][] for the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function unsetServiceBuilder(string $serviceName) : void;

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
