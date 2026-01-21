<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocContainer_][] affords obtaining services by name, whether as shared
 * instances or new unshared instances.
 *
 * - Directives:
 *
 *     - Implementations MUST retain an instance of the container itself under
 *       a `$serviceName` of `IocContainer::class`.
 *
 * - Notes:
 *
 *     - **This interface does not afford service registration.** The container
 *       will need to obtain services from [_IocServices_][] somehow:
 *
 *         - Some implementors will prefer an "open" approach, where the
 *           services are set and modified directly on the container
 *           itself, in which case implementing both [_IocContainer_][] and
 *           [_IocServices_][], or a container implementation extending a
 *           services implementation, is reasonable.
 *
 *         - Other implementors will prefer a "closed" approach, where an
 *           [_IocServices_][] implementation is encapsulated but not exposed by
 *           an [_IocContainer_][] implementation.
 *
 *     - **Keep the container itself as a service.** This allows factory
 *       and builder services to depend on the container; it may be easiest
 *       to do so as part of `__construct()`.
 *
 * @phpstan-import-type ioc_service_name_string from IocTypeAliases
 * @phpstan-import-type ioc_service_object from IocTypeAliases
 */
interface IocContainer
{
    /**
     * Is the container capable of returning a shared instance of the
     * service?
     *
     * - Directives:
     *
     *     - Implementations MUST convert the `$serviceName` argument to its
     *       alias, if an alias exists for that `$serviceName`.
     *
     *     - Implementations MUST return `true` if ...
     *
     *         - the container has access to a shared instance of
     *           `$serviceName`; or,
     *
     *         - the container has access to a service builder for
     *           `$serviceName` that has a service factory; or,
     *
     *         - the `$serviceName` is an instantiable class.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasService(string $serviceName) : bool;

    /**
     * Returns a shared instance of a service, instantiating it if
     * necessary.
     *
     * - Directives:
     *
     *     - Implementations MUST convert the `$serviceName` argument to its
     *       alias, if an alias exists for that `$serviceName`.
     *
     *     - Implementations MUST throw [_IocThrowable_][] if the
     *       container cannot return a shared instance of the service.
     *
     *     - Implementations MUST return the same instance of the service
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
     * Returns a new instance of the service.
     *
     * - Directives:
     *
     *     - Implementations MUST convert the `$serviceName` argument to its
     *       alias, if an alias exists for that `$serviceName`.
     *
     *     - Implementations MUST throw [_IocThrowable_][] if the
     *       container cannot return a new instance of the service.
     *
     *     - Implementations MUST return a different instance of the
     *       service each time this method is called.
     *
     * - Notes:
     *
     *     - **TBD** Typically via a service builder.
     *
     *     - **TBD** Circular tracking.
     *
     * @param ioc_service_name_string $serviceName
     * @param mixed[] $serviceArgs
     * @return ioc_service_object
     */
    public function newService(
        string $serviceName,
        array $serviceArgs = [],
    ) : object;
}
