<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocTypeAliases_][] defines PHPStan type aliases to aid static analysis.
 *
 * - ```
 *   ioc_service_name_string class-string<T>|non-empty-string
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
 * @phpstan-type ioc_service_name_string class-string<T>|non-empty-string
 *
 * @phpstan-type ioc_service_object ($serviceName is class-string<T> ? T : object)
 */
interface IocTypeAliases
{
}
