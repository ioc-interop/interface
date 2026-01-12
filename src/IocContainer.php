<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocContainer_][] interface affords obtaining services by
 * name, whether as shared instances or new unshared instances.
 *
 * - Directives:
 *
 *     - Implementations MUST convert each `$serviceName` argument to its alias, if
 *       an alias exists for that `$serviceName`.
 *
 * @phpstan-import-type ioc_service_name_string from IocTypeAliases
 * @phpstan-import-type ioc_service_object from IocTypeAliases
 */
interface IocContainer
{
    /**
     * Is the container capable of returning a shared instance of the
     * `$serviceName`?
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasService(string $serviceName) : bool;

    /**
     * Returns a shared instance of the `$serviceName`, instantiating it if
     * necessary.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] if the
     *       container cannot return a shared instance of the `$serviceName`.
     *
     *     - Implementations MUST return the same instance of the `$serviceName`
     *       service each time this method is called.
     *
     * - Notes:
     *
     *     - **Create and retain a new instance if necessary.** In practice,
     *       this likely means calling `newService($serviceName)` and holding
     *       onto the newly-created instance for later calls to
     *       `getService($serviceName)`.
     *
     * @param ioc_service_name_string $serviceName
     * @return ioc_service_object
     */
    public function getService(string $serviceName) : object;

    /**
     * Returns a new instance of the `$serviceName`.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] if the
     *       container cannot return a new instance of the `$serviceName`.
     *
     *     - Implementations MUST return a different instance of the
     *       `$serviceName` each time this method is called.
     *
     * - Notes:
     *
     *     - **Service instantiation logic is not specified.** Implementations
     *       might use autowiring, configuration, builders, or some other means
     *       to create the service. The creation logic might be part of
     *       the container, or it might be part of some other subsystem.
     *
     * @param ioc_service_name_string $serviceName
     * @return ioc_service_object
     */
    public function newService(string $serviceName) : object;
}
