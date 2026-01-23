<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocServiceBuilder_][] affords building a service, including both
 * instantiation and extended post-instantiation logic.
 *
 * @phpstan-import-type ioc_service_factory_callable from IocTypeAliases
 * @phpstan-import-type ioc_service_extender_callable from IocTypeAliases
 */
interface IocServiceBuilder
{
    /**
     * Is there a factory that instantiates the service?
     */
    public function hasServiceFactory() : bool;

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
    public function getServiceFactory() : callable;

    /**
     * Sets the factory that instantiates the service.
     *
     * - Notes:
     *
     *     - **The `callable` type allows for a wide range of implementations.**
     *       Cf. the <https://php.net/callable> documentation for more.
     */
    public function setServiceFactory(callable $serviceFactory) : self;

    /**
     * Invokes the service factory callable that instantiates the service.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] if there is no
     *       factory for the service.
     */
    public function runServiceFactory(IocContainer $ioc) : object;

    /**
     * Unsets the factory that instantiates the service.
     *
     * @return $this
     */
    public function unsetServiceFactory() : self;

    /**
     * Are there any post-instantiation extenders for the service?
     */
    public function hasServiceExtenders() : bool;

    /**
     * Returns the post-instantiation extenders for the service.
     *
     * @return ioc_service_extender_callable[]
     */
    public function getServiceExtenders() : array;

    /**
     * Sets all post-instantiation extenders for the service.
     *
     * @param ioc_service_extender_callable[] $serviceExtenders
     * @return $this
     */
    public function setServiceExtenders(array $serviceExtenders) : self;

    /**
     * Unsets all post-instantiation extenders for the service.
     *
     * @return $this
     */
    public function unsetServiceExtenders() : self;

    /**
     * Adds a single service extender to the builder.
     *
     * - Notes:
     *
     *     - **The `callable` type allows for a wide range of implementations.**
     *       Cf. the <https://php.net/callable> documentation for more.
     *
     * @param ioc_service_extender_callable $serviceExtender
     * @return $this
     */
    public function addServiceExtender(callable $serviceExtender) : self;

    /**
     * Creates and returns a new instance of the service.
     *
     * - Notes:
     *
     *     - **TBD** Instantiate (by factory or resolver) then apply extenders
     *       then return.
     */
    public function buildService(IocContainer $ioc) : object;
}
