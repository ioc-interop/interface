<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocTypeAliases_][] defines PHPStan type aliases to aid static analysis.
 *
 * - ```
 *   ioc_service_extender_callable callable(object,IocContainer):object
 *   ```
 *     - A `callable` for service post-instantiation logic; e.g. to set a
 *       property, call a setter or initializer method, decorate the service,
 *       etc.
 *
 * - ```
 *   ioc_service_factory_callable callable(IocContainer):object
 *   ```
 *     - A `callable` for service instantiation logic.
 *
 * - ```
 *   ioc_service_lifetime_string IocServices::SCOPED|IocServices::SINGLETON|IocServices::TRANSIENT
 *   ```
 *     - A `string` indicating the lifetime of a service.
 *
 * - ```
 *   ioc_service_name_string class-string<T>|string
 *   ```
 *     - A `class-string` or `string` name for a service.
 *
 * - ```
 *   ioc_service_object ($serviceName is class-string<T> ? T : object)
 *   ```
 *     - The service `object` for a given service name.
 *
 * @template T of object
 *
 * @phpstan-type ioc_service_extender_callable callable(IocContainer, T):T
 *
 * @phpstan-type ioc_service_factory_callable callable(IocContainer,mixed[]=):object
 *
 * @phpstan-type ioc_service_name_string class-string<T>|string
 *
 * @phpstan-type ioc_service_lifetime_string IocServices::SCOPED|IocServices::SINGLETON|IocServices::TRANSIENT
 *
 * @phpstan-type ioc_service_object ($serviceName is class-string<T> ? T : object)
 */
interface IocTypeAliases
{
}
