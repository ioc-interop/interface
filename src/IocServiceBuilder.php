<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * The [_IocServiceBuilder_][] interface affords building a service,
 * including both instantiation and extended post-instantiation logic.
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
     *     - **TBD** MUST throw if no factory.
     *
     * @return ioc_service_factory_callable
     */
    public function getServiceFactory() : callable;

    /**
     * Sets the factory that instantiates the service.
     *
     * - Notes:
     *
     *     - **TBD** Takes precedence over any other instantiation logic.
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
     *     - **TBD** If no factory, MUST throw.
     *
     *     - **TBD** If $serviceArgs not empty, and factory cannot receive
     *       $serviceArgs as 2nd parameter, MUST throw.
     *
     * - Notes:
     *
     *     - **TBD** Only check second arg; IocContainer is assumed, but args
     *       param may not be present, and should warn when newServiceWithArgs()
     *       cannot honor the args.
     *
     * @param mixed[] $serviceArgs
     */
    public function runServiceFactory(
        IocContainer $ioc,
        array $serviceArgs = []
    ) : object;

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
     *
     * @param mixed[] $serviceArgs
     */
    public function buildService(
        IocContainer $ioc,
        array $serviceArgs = [],
    ) : object;
}
