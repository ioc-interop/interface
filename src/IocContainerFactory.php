<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocContainerFactory_][] interface affords obtaining a new instance of
 * [_IocContainer_][].
 */
interface IocContainerFactory
{
    /**
     * Returns a new instance of [_IocContainer_][].
     *
     * - Notes:
     *
     *     - **Container instantiation logic is not specified.** Implementations
     *       might use providers, configuration files, attribute or annotation
     *       collection, or some other means to create and populate a container.
     *       Implementations might also choose to return a compiled or otherwise
     *       reconstituted container.
     */
    public function newContainer() : IocContainer;
}
