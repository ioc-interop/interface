<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocInstanceFactory_][] affords instantiating a class.
 */
interface IocInstanceFactory
{
    /**
     * Returns a new instance of the `$class` with `$arguments` constructor
     * argument overrides.
     *
     * - Notes:
     *
     *     - **Use this for custom factory classes.** The [_IocClassResolver_][]
     *       needs an [_IocContainer_][] as its first parameter, this method
     *       does not. In turn, that means this class probably ought to be
     *       constructed with both a container and a class resolver, so that
     *       this method can forward to the class resolver with the container.
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
