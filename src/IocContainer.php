<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocContainer_][] affords obtaining services by name.
 *
 * - Directives:
 *
 *     - Implementations MUST retain an instance of the container itself under
 *       a `$serviceName` of `IocContainer::class`.
 *
 * - Notes:
 *
 *     - **This interface does not afford service registration.** The container
 *       will need to obtain services somehow, typically but not necessarily
 *       from [_IocServices_][]. For example:
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
 *     - **Keep the container itself as a service.** This allows consumer
 *       factories, builders, and locators to depend on the container. It may be
 *       easiest to do so as part of `__construct()`.
 *
 * @phpstan-import-type ioc_service_name_string from IocTypeAliases
 * @phpstan-import-type ioc_service_object from IocTypeAliases
 */
interface IocContainer
{
    /**
     * Is the container able to return an instance of the service?
     *
     * - Directives:
     *
     *     - Implementations MUST convert the `$serviceName` argument to its
     *       alias, if an alias exists for that `$serviceName`.
     *
     * - Notes:
     *
     *     - **The logic for this method is expressly unspecified.** Typically
     *       this will be accomplished by checking some combination of
     *       `hasInstance()`, `hasDefinition()`, or `isResolvable()`.
     *       However, different implementations (e.g. compiled containers) may
     *       use some other approach.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasService(string $serviceName) : bool;

    /**
     * Returns an instance of a service, instantiating it if necessary.
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
     *       each time this method is called if the service lifetime **is not**
     *       `IocServices::TRANSIENT`.
     *
     *     - Implementations MUST return a new instance of the service
     *       each time this method is called if the service lifetime **is**
     *       `IocServices::TRANSIENT`.
     *
     * - Notes:
     *
     *     - **Create a new service instance if necessary.** Typically this
     *       will be accomplished by calling `getDefinition($serviceName)`
     *       and then `buildInstance()`. However, different implementations
     *       (e.g. compiled containers) may use some other approach.
     *
     * @param ioc_service_name_string $serviceName
     * @return ioc_service_object
     */
    public function getService(string $serviceName) : object;
}
