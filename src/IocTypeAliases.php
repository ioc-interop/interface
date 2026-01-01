<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocTypeAliases_][] interface defines PHPStan type aliases
 * to aid static analysis.
 *
 * - ```
 *   ioc_service_factory_callable callable(IocContainer):object
 *   ```
 *     - A `callable` to create and return a new instance of a service.
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
 * @phpstan-type ioc_service_factory_callable callable(IocContainer):object
 * @phpstan-type ioc_service_name_string class-string<T>|string
 * @phpstan-type ioc_service_object ($serviceName is class-string<T> ? T : object)
 */
interface IocTypeAliases
{
}
