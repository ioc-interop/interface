<?php
declare(strict_types=1);

namespace IocInterop\Interface;

use IocInterop\Interface\IocContainer;
use IocInterop\Interface\IocTypeAliases;

/**
 * [_IocResolver_][] affords resolving a class name to a new instance of
 * that class.
 *
 * @phpstan-import-type ioc_service_name_string from IocTypeAliases
 * @phpstan-import-type ioc_service_object from IocTypeAliases
 */
interface IocResolver
{
    /**
     * Does the `$class` exist, and is it instantiable?
     *
     * @param string $class
     */
    public function isResolvable(string $class) : bool;

    /**
     * Given an [_IocContainer_][] to locate constructor dependencies,
     * returns a new instance of the `$class` with `$arguments` constructor
     * argument overrides.
     *
     * - Directives:
     *
     *     - Implementations MUST support constructor injection using logic
     *       equivalent to that specified by [_IocParametersResolver_][].
     *
     *     - Implementations MAY support other forms of injection, such as
     *       setter injection, property injection, and so on.
     *
     *     - Implementations MUST throw [_IocThrowable_][] if the `$class`
     *       cannot be resolved.
     *
     * @template T of object
     * @param string $class
     * @param mixed[] $arguments
     * @return ($class is class-string<T> ? T : object)
     */
    public function resolve(
        IocContainer $ioc,
        string $class,
        array $arguments = [],
    ) : object;
}
