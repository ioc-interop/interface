<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocInstanceFactory_][] affords instantiating a class.
 *
 * - Notes:
 *
 *     - **The instance factory does not "build" or "retain" a new instance.**
 *       It does not specify applying any post-instantiation logic, as with
 *       [_IocDefinition_][]. Likewise, it does not "retain" the new
 *       instance as with [_IocContainer_]. It only instantiates and returns.
 *
 *     - **The instance factory is not a resolver.** However, implementations
 *       are likely to compose an [_IocResolver_][] and [_IocContainer_][] to
 *       support instantiation logic.
 */
interface IocInstanceFactory
{
    /**
     * Returns a new instance of the `$class` with `$arguments` constructor
     * argument overrides.
     *
     * @template T of object
     * @param string $class
     * @param mixed[] $arguments
     * @return ($class is class-string<T> ? T : object)
     */
    public function newInstance(
        string $class,
        array $arguments = [],
    ) : object;
}
