<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocServices_][] affords a registry of service instances, definitions, and
 * aliases.
 *
 * - Directives:
 *
 *     - Implementations MUST set an instance of an [_IocResovler_[] using a
 *       $serviceName` of `IocResolver::class`.
 *
 * - Notes:
 *
 *     - **"Prime" the services with a resolver.** Because of the necessarily
 *       circular relationship regarding service resolution, implementations
 *       will need access to a pre-created [_IocResolver_][]. It may be easiest
 *       to do so as part of `__construct()`.
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
     *     - **Create a new definition if necessary.** In practice, this
     *       likely means calling `newDefinition($serviceName)` and then
     *       retaining that instance for later retrieval.
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
     *     - Implementations MUST return the final alias in the alias chain
     *       for the `$serviceName`.
     *
     * - Notes:
     *
     *     - **Chained aliases are allowed.** That is, one alias can lead to
     *       another, and that one to yet another, and so on. This means
     *       implementations will have to track through those aliases to arrive
     *       at a final or terminal alias for the `$serviceName`.
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
     *     - Implementations MUST attempt to detect if adding the `$alias` would
     *       result in an infinite alias cycle; on detection, implementations
     *       MUST throw [_IocThrowable_][].
     *
     * - Notes:
     *
     *     - **Chained aliases are allowed.** That is, one alias can lead to
     *       another, and that one to yet another, and so on. To prevent an
     *       infinite loop, implementations will have to track through the
     *       aliases to find if the `$alias` would end up back at itself.
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
