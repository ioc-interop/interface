<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocServices_][] affords a registry of service instances, definitions, and
 * aliases.
 *
 * - Notes:
 *
 *     - **TBD** Prime the implementation with an [_IocResolver_][]
 *       instance.
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
    public function hasInstance(string $serviceName) : bool;

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
    public function getInstance(string $serviceName) : object;

    /**
     * Sets the shared instance of the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     * @param ioc_service_object $instance
     */
    public function setInstance(
        string $serviceName,
        object $instance,
    ) : void;

    /**
     * Unsets the shared instance of the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function unsetInstance(string $serviceName) : void;

    /**
     * Has an [_IocDefinition_][] for the `$serviceName` been set?
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasDefinition(string $serviceName) : bool;

    /**
     * Returns the [_IocDefinition_][] for the `$serviceName`, instantiating
     * it if needed.
     *
     * - Notes:
     *
     *     - **TBD** Create using newDefinition() and retain for later
     *       return.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function getDefinition(string $serviceName) : IocDefinition;

    /**
     * Returns a new [_IocDefinition_][] for the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function newDefinition(string $serviceName) : IocDefinition;

    /**
     * Sets the [_IocDefinition_][] for the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function setDefinition(
        string $serviceName,
        IocDefinition $definition,
    ) : void;

    /**
     * Unsets the [_IocDefinition_][] for the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function unsetDefinition(string $serviceName) : void;

    /**
     * Has an alias for the `$serviceName` been set?
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasAlias(string $serviceName) : bool;

    /**
     * Returns the alias for the `$serviceName`.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] an alias for the
     *       `$serviceName` is not available.
     *
     *     - **TBD** Recursive resolution.
     *
     * - Notes:
     *
     *     - **TBD** Rescursive aliases are allowed.
     *
     * @param ioc_service_name_string $serviceName
     * @return ioc_service_name_string
     */
    public function getAlias(string $serviceName) : string;

    /**
     * Sets the alias for one `$serviceName` to another service.
     *
     * - Directives:
     *
     *     - **TBD** Circular tracking.
     *
     * - Notes:
     *
     *     - **TBD** Rescursive aliases are allowed.
     *
     * @param ioc_service_name_string $serviceName
     * @param ioc_service_name_string $alias
     */
    public function setAlias(string $serviceName, string $alias) : void;

    /**
     * Unsets the alias for the `$serviceName`.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function unsetAlias(string $serviceName) : void;
}
