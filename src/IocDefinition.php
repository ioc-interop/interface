<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocDefinition_][] affords building a service instance, including both
 * instantiation logic and extended post-instantiation logic.
 *
 * @phpstan-import-type ioc_service_factory_callable from IocTypeAliases
 * @phpstan-import-type ioc_service_extender_callable from IocTypeAliases
 */
interface IocDefinition
{
    /**
     * Is there a factory that instantiates the service?
     */
    public function hasFactory() : bool;

    /**
     * Returns the factory that instantiates the service.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] if there is no
     *       factory for the service.
     *
     * @return ioc_service_factory_callable
     */
    public function getFactory() : callable;

    /**
     * Sets the factory that instantiates the service.
     *
     * - Notes:
     *
     *     - **The `callable` type allows for a wide range of implementations.**
     *       Cf. the <https://php.net/callable> documentation for more.
     */
    public function setFactory(callable $factory) : self;

    /**
     * Unsets the factory that instantiates the service.
     *
     * @return $this
     */
    public function unsetFactory() : self;

    /**
     * Are there any post-instantiation extenders for the service?
     */
    public function hasExtenders() : bool;

    /**
     * Returns the post-instantiation extenders for the service.
     *
     * @return ioc_service_extender_callable[]
     */
    public function getExtenders() : array;

    /**
     * Sets all post-instantiation extenders for the service.
     *
     * @param ioc_service_extender_callable[] $extenders
     * @return $this
     */
    public function setExtenders(array $extenders) : self;

    /**
     * Unsets all post-instantiation extenders for the service.
     *
     * @return $this
     */
    public function unsetExtenders() : self;

    /**
     * Adds a single post-instantiation extender for the service.
     *
     * - Notes:
     *
     *     - **The `callable` type allows for a wide range of implementations.**
     *       Cf. the <https://php.net/callable> documentation for more.
     *
     * @param ioc_service_extender_callable $extender
     * @return $this
     */
    public function addExtender(callable $extender) : self;

    /**
     * Builds a new instance of the service.
     *
     * - Directives:
     *
     *     - Implementations MUST instantiate the service with the defined
     *       factory if one is set; otherwise, implmentations SHOULD instantiate
     *       the service using an [_IocResolver_][] implementation.
     *
     *     - Implementation MUST apply all defined extenders to the
     *       newly-instantiated service.
     */
    public function buildInstance(IocContainer $ioc) : object;
}
