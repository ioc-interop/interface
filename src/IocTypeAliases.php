<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocTypeAliases_][] provides custom PHPStan types to aid static analysis.
 *
 * - ```
 *   ioc_service_name_string non-empty-string
 *   ```
 *     - A `class-string` or non-empty `string` name for a service.
 *
 * - ```
 *   ioc_service_object ($serviceName is class-string<T> ? T : object)
 *   ```
 *     - The service `object` for a given service name.
 *
 * @template T of object
 *
 * @phpstan-type ioc_service_name_string non-empty-string
 *
 * @phpstan-type ioc_service_object ($serviceName is class-string<T> ? T : object)
 */
interface IocTypeAliases
{
}
