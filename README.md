# Ioc-Interop Standard Interface Package

Ioc-Interop provides an interoperable package of standard interfaces for
inversion-of-control (IOC) container functionality. It reflects, refines, and
reconciles the common practices identified within
[several pre-existing projects][README-RESEARCH.md].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [BCP 14][] ([RFC 2119][], [RFC 8174][]).

## Interfaces

This package defines the following interfaces:

- [_IocContainer_][] affords obtaining services by name, whether as shared instances or new unshared instances.

- [_IocServices_][] affords a registry of service instances, builders, and aliases.

- [_IocServicesProvider_][] affords provision of service instances, builders, and aliases to an [_IocServices_][] instance.

- [_IocServiceBuilder_][] affords building a service, including both instantiation and extended post-instantiation logic.

- [_IocClassResolver_][] affords resolving a class name to a new instance of that class.

- [_IocParametersResolver_][] affords resolving an array of parameters to an array of named arguments.

- [_IocParameterResolver_][] affords resolving a parameter to an argument value.

- [_IocContainerFactory_][] affords obtaining a new instance of [_IocContainer_][].

- [_IocThrowable_][] extends [_Throwable_][] to mark an [_Exception_][] as IOC-related. It adds no class members.

- [_IocTypeAliases_][] defines PHPStan type aliases to aid static analysis.

### _IocContainer_

[_IocContainer_][] affords obtaining services by name, whether as shared
instances or new unshared instances.

- Directives:

    - Implementations MUST retain an instance of the container itself under
      a `$serviceName` of `IocContainer::class`.

- Notes:

    - **This interface does not afford service registration.** The container
      will need to obtain services from [_IocServices_][] somehow:

        - Some implementors will prefer an "open" approach, where the
          services are set and modified directly on the container
          itself, in which case implementing both [_IocContainer_][] and
          [_IocServices_][], or a container implementation extending a
          services implementation, is reasonable.

        - Other implementors will prefer a "closed" approach, where an
          [_IocServices_][] implementation is encapsulated but not exposed by
          an [_IocContainer_][] implementation.

    - **Keep the container itself as a service.** This allows factory
      and builder services to depend on the container; it may be easiest
      to do so as part of `__construct()`.

#### _IocContainer_ Methods

- ```php
  public function hasService(ioc_service_name_string $serviceName) : bool;
  ```
    - Is the container capable of returning a shared instance of the
    service?

    - Directives:

        - Implementations MUST convert the `$serviceName` argument to its
          alias, if an alias exists for that `$serviceName`.

        - Implementations MUST return `true` if ...

            - the container has access to a shared instance of
              `$serviceName`; or,

            - the container has access to a service builder for
              `$serviceName` that has a service factory; or,

            - the `$serviceName` is an instantiable class.

- ```php
  public function getService(
      ioc_service_name_string $serviceName,
  ) : ioc_service_object;
  ```
    - Returns a shared instance of a service, instantiating it if
    necessary.

    - Directives:

        - Implementations MUST convert the `$serviceName` argument to its
          alias, if an alias exists for that `$serviceName`.

        - Implementations MUST throw [_IocThrowable_][] if the
          container cannot return a shared instance of the service.

        - Implementations MUST return the same instance of the service
          service each time this method is called.

    - Notes:

        - **Create and retain a new instance if necessary.** In practice,
          this likely means calling `newService($serviceName)` and holding
          onto the newly-created instance for later calls to
          `getService($serviceName)`.

- ```php
  public function newService(
      ioc_service_name_string $serviceName,
      mixed[] $arguments = [],
  ) : ioc_service_object;
  ```
    - Returns a new instance of the service.

    - Directives:

        - Implementations MUST convert the `$serviceName` argument to its
          alias, if an alias exists for that `$serviceName`.

        - Implementations MUST throw [_IocThrowable_][] if the
          container cannot return a new instance of the service.

        - Implementations MUST return a different instance of the
          service each time this method is called.

    - Notes:

        - **TBD** Typically via a service builder.

        - **TBD** Circular tracking.

### _IocServices_

[_IocServices_][] affords a registry of service instances, builders, and
aliases.

- Notes:

    - **TBD** Prime the implementation with an [_IocClassResolver_][]
      instance.

#### _IocServices_ Methods

- ```php
  public function hasServiceInstance(ioc_service_name_string $serviceName) : bool;
  ```
    - Has a shared instance of the `$serviceName` been set?

- ```php
  public function getServiceInstance(
      ioc_service_name_string $serviceName,
  ) : ioc_service_object;
  ```
    - Returns the shared instance of the `$serviceName`.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] if a shared instance
          of the `$serviceName` is not available.

- ```php
  public function setServiceInstance(
      ioc_service_name_string $serviceName,
      ioc_service_object $instance,
  ) : void;
  ```
    - Sets the shared instance of the `$serviceName`.

- ```php
  public function unsetServiceInstance(
      ioc_service_name_string $serviceName,
  ) : void;
  ```
    - Unsets the shared instance of the `$serviceName`.

- ```php
  public function hasServiceBuilder(ioc_service_name_string $serviceName) : bool;
  ```
    - Has an [_IocServiceBuilder_][] for the `$serviceName` been set?

- ```php
  public function getServiceBuilder(
      ioc_service_name_string $serviceName,
  ) : IocServiceBuilder;
  ```
    - Returns the [_IocServiceBuilder_][] for the `$serviceName`, instantiating
    it if needed.

    - Notes:

        - **TBD** Create using newServiceBuilder() and retain for later
          return.

- ```php
  public function newServiceBuilder(
      ioc_service_name_string $serviceName,
  ) : IocServiceBuilder;
  ```
    - Returns a new [_IocServiceBuilder_][] for the `$serviceName`.

- ```php
  public function setServiceBuilder(
      ioc_service_name_string $serviceName,
      IocServiceBuilder $serviceBuilder,
  ) : void;
  ```
    - Sets the [_IocServiceBuilder_][] for the `$serviceName`.

- ```php
  public function unsetServiceBuilder(
      ioc_service_name_string $serviceName,
  ) : void;
  ```
    - Unsets the [_IocServiceBuilder_][] for the `$serviceName`.

- ```php
  public function hasServiceAlias(ioc_service_name_string $serviceName) : bool;
  ```
    - Has an alias for the `$serviceName` been set?

- ```php
  public function getServiceAlias(
      ioc_service_name_string $serviceName,
  ) : ioc_service_name_string;
  ```
    - Returns the alias for the `$serviceName`.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] an alias for the
          `$serviceName` is not available.

        - **TBD** Recursive resolution.

    - Notes:

        - **TBD** Rescursive aliases are allowed.

- ```php
  public function setServiceAlias(
      ioc_service_name_string $serviceName,
      ioc_service_name_string $serviceAlias,
  ) : void;
  ```
    - Sets the alias for one `$serviceName` to another service.

    - Directives:

        - **TBD** Circular tracking.

    - Notes:

        - **TBD** Rescursive aliases are allowed.

- ```php
  public function unsetServiceAlias(ioc_service_name_string $serviceName) : void;
  ```
    - Unsets the alias for the `$serviceName`.

### _IocServicesProvider_

[_IocServicesProvider_][] affords provision of service instances, builders,
and aliases to an [_IocServices_][] instance.

#### _IocServicesProvider_ Methods

- ```php
  public function provideServices(IocServices $services) : void;
  ```
    - Provides service instances, builders, and aliases to the `$services`.

    - Notes:

        - **Provision includes a wide range of activity.** The implementation
          can set, unset, replace, modify, etc. the instances, builders, and
          aliases in the `$services`.

### _IocServiceBuilder_

[_IocServiceBuilder_][] affords building a service, including both
instantiation and extended post-instantiation logic.

#### _IocServiceBuilder_ Methods

- ```php
  public function hasServiceFactory() : bool;
  ```
    - Is there a factory that instantiates the service?

- ```php
  public function getServiceFactory() : ioc_service_factory_callable;
  ```
    - Returns the factory that instantiates the service.

    - Directives:

        - **TBD** MUST throw if no factory.

- ```php
  public function setServiceFactory(callable $serviceFactory) : self;
  ```
    - Sets the factory that instantiates the service.

    - Notes:

        - **TBD** Takes precedence over any other instantiation logic.

        - **The `callable` type allows for a wide range of implementations.**
          Cf. the <https://php.net/callable> documentation for more.

- ```php
  public function runServiceFactory(
      IocContainer $ioc,
      mixed[] $arguments = [],
  ) : object;
  ```
    - Invokes the service factory callable that instantiates the service.

    - Directives:

        - **TBD** If no factory, MUST throw.

        - **TBD** If $arguments not empty, and factory cannot receive
          $arguments as 2nd parameter, MUST throw.

    - Notes:

        - **TBD** Only check second arg; IocContainer is assumed, but args
          param may not be present, and should warn when newServiceWithArgs()
          cannot honor the args.

- ```php
  public function unsetServiceFactory() : $this;
  ```
    - Unsets the factory that instantiates the service.

- ```php
  public function hasServiceExtenders() : bool;
  ```
    - Are there any post-instantiation extenders for the service?

- ```php
  public function getServiceExtenders() : ioc_service_extender_callable[];
  ```
    - Returns the post-instantiation extenders for the service.

- ```php
  public function setServiceExtenders(
      ioc_service_extender_callable[] $serviceExtenders,
  ) : $this;
  ```
    - Sets all post-instantiation extenders for the service.

- ```php
  public function unsetServiceExtenders() : $this;
  ```
    - Unsets all post-instantiation extenders for the service.

- ```php
  public function addServiceExtender(
      ioc_service_extender_callable $serviceExtender,
  ) : $this;
  ```
    - Adds a single service extender to the builder.

    - Notes:

        - **The `callable` type allows for a wide range of implementations.**
          Cf. the <https://php.net/callable> documentation for more.

- ```php
  public function buildService(
      IocContainer $ioc,
      mixed[] $arguments = [],
  ) : object;
  ```
    - Creates and returns a new instance of the service.

    - Notes:

        - **TBD** Instantiate (by factory or resolver) then apply extenders
          then return.

### _IocClassResolver_

[_IocClassResolver_][] affords resolving a class name to a new instance of
that class.

#### _IocClassResolver_ Methods

- ```php
  public function isClassResolvable(string $class) : bool;
  ```
    - Does the `$class` exist, and is it instantiable?

- ```php
  public function resolveClass(
      IocInterop\Interface\IocContainer $ioc,
      string $class,
      mixed[] $arguments = [],
  ) : ($class is class-string<T> ? T
  ```
    - Given an [_IocContainer_][] to locate constructor dependencies,
    returns a new instance of the `$class` with `$arguments` constructor
    argument overrides.

    - Directives:

        - Implementations MUST support constructor injection using logic
          equivalent to that specified by [_IocParametersResolver_][].

        - Implementations MAY support other forms of injection, such as
          setter injection, property injection, and so on.

        - Implementations MUST throw [_IocThrowable_][] if the `$class`
          cannot be resolved.

### _IocParametersResolver_

[_IocParametersResolver_][] affords resolving an array of parameters to an
array of named arguments.

#### _IocParametersResolver_ Methods

- ```php
  public function resolveParameters(
      IocInterop\Interface\IocContainer $ioc,
      ReflectionParameter[] $parameters,
      mixed[] $arguments = [],
  ) : mixed[];
  ```
    - Resolves an array of parameters to an array of named parameter arguments,
    allowing for an array of override arguments.

    - Directives:

        - Implementations MUST NOT attempt to resolve parameters that already
          exist by name in the `$arguments` array keys.

        - When resolving a parameter, implementations MUST do so using logic
          equivalent to that specified by [_IocParameterResolver_][].

        - Implementations MUST return an array of arguments keyed by the
          parameter names.

    - Notes:

        - **Do not replace existing `$arguments`.** If an argument has
          already been given for a parameter name, there is no need to
          resolve the related parameter.

### _IocParameterResolver_

[_IocParameterResolver_][] affords resolving a parameter to an argument
value.

- Directives:

    - Implementations MUST resolve parameters in this order:

        - If the parameter has an [_Attribute_][] that implements
          [_IocParameterResolver_][], implementations MUST resolve the
          parameter using that attribute.

        - Otherwise, if the parameter type is a [_ReflectionNamedType_][],
          and the container has a service for that type, implementations MUST
          resolve the parameter to that service.

        - Otherwise, implementations MAY attempt to resolve the parameter
          using implementation-specific logic; such logic is expressly not
          defined herein.

        - Otherwise, if the parameter has a default value, implementations
          MUST resolve the parameter to that value.

    - Implementations MUST throw [_IocThrowable_][] if the parameter cannot
      be resolved.

- Notes:

    - **This interface can be implemented as an attribute.** Doing so allows
      implementors to define custom resolution approaches for consumers to
      apply to specific parameters. For example, implementors may declare a
      `#[GetEnv($name)]` attribute to resolve the parameter to an environment
      value.

#### _IocParameterResolver_ Methods

- ```php
  public function resolveParameter(
      IocInterop\Interface\IocContainer $ioc,
      ReflectionParameter $parameter,
  ) : mixed;
  ```
    - Resolves the parameter to an argument value.

    - Notes:

        - **The return is `mixed`.** The resolved value might be anything at
          at all. This allows (e.g.) attribute implementations to obtain a
          service from the container, and then obtain a value from that
          service.

### _IocContainerFactory_

[_IocContainerFactory_][] affords obtaining a new instance of
[_IocContainer_][].

#### _IocContainerFactory_ Methods

- ```php
  public function newContainer() : IocContainer;
  ```
    - Returns a new instance of [_IocContainer_][].

    - Notes:

        - **Container instantiation logic is not specified.** Implementations
          might use providers, configuration files, attribute or annotation
          collection, or some other means to create and populate a container.
          Implementations might also choose to return a compiled or otherwise
          reconstituted container.

### _IocThrowable_

[_IocThrowable_][] extends [_Throwable_][] to mark an [_Exception_][] as
IOC-related. It adds no class members.

### _IocTypeAliases_

[_IocTypeAliases_][] defines PHPStan type aliases to aid static analysis.

- ```
  ioc_service_extender_callable callable(object,IocContainer):object
  ```
    - A `callable` for service post-instantiation logic; e.g. to set a
      property, call a setter or initializer method, decorate the service,
      etc.

- ```
  ioc_service_factory_callable callable(IocContainer):object|callable(IocContainer,mixed[]=):object
  ```
    - A `callable` for service instantiation logic, with or without a
      parameter for optional override constructor arguments.

- ```
  ioc_service_name_string class-string<T>|string
  ```
    - A `class-string` or `string` name for a service.

- ```
  ioc_service_object ($serviceName is class-string<T> ? T : object)
  ```
    - The service `object` for a given service name.

## Implementations

- Directives:

    - Implementations MAY define additional class members not defined in these
      interfaces.

- Notes:

    - **Reference implementations** may be found at <https://github.com/ioc-interop/impl>.

## Q & A

### How is Ioc-Interop different from PSR-11?

[PSR-11][] is an earlier recommendation that offers an interface to `get`
items from a container, and to see if that container `has` a particular item.
The Ioc-Interop standard is more expansive.

- Ioc-Interop offers separate interface methods for getting shared service
  instances and creating new service instances. PSR-11 defines only `get()`,
  which may do either or both depending on the implementation.

- Ioc-Interop offers an interface to set/get/has/unset service instances,
  builders, and aliases. PSR-11 offers no such interface.

- Ioc-Interop is intended to contain only services (`object`). PSR-11
  is intended to contain anything (`mixed`).

- Ioc-Interop offers a container factory. PSR-11 offers none.

- Ioc-Interop defines only one _Throwable_ interface; PSR-11 defines two
  exception interfaces.

### Is Ioc-Interop compatible with PSR-11?

No, in the sense that the method names, signatures, and intents are different.

Yes, in the sense that both may be implemented on the same class; the method
names are different, and so are non-conflicting.

### Is _IocContainer_ a Dependency Injection system or a Service Locator?

_IocContainer_ acts a Service Locator only when it is used as a dependency in
order to retrieve other dependencies from it.

### Why does _IocContainer_ disallow non-object values?

TBD: To maintain conceptual integrity and consistent expectations. Given that
`getService()` returns a shared service, and `newService()` returns a new
instance, what does it mean to "get" a shared string value or a "new" string
value? How then to get non-object configuration values? Create config objects as services.
How to inject non-object values as constructor args? Consider _IocParameterResolver_
attributes.

### Why does _IocContainer_ define `newService()` instead of `make()`, `create()`, or `build()` ?

The researched projects use several different terms to indicate that a new
service will be returned: `build` (2 projects), `create` (3), `get` (6),
`make` (3), and `new` (2).

The terms `get` and `make` are ambiguous in the researched projects. They might:

- create a new service every time;

- return a shared service every time;

- create a new service the first time and return that same instance
  every time thereafter; or,

- do some combination of the above, depending how the service was defined.

The terms `build` and `create` are less-ambiguous, but are much less common.

In comparison, `newService()` is easily disambiguated from `getService()`.
Ioc-Interop stipulates that former always returns a new instance, and the latter
always returns a shared instance (after creating it if necessary).

### Why is _IocContainer_ separate from _IocServices_?

Whereas _IocContainer_ is for *obtaining* instances, _IocServices_ is for
*registering* the instances, builders, and aliases involved in producing the
services to be obtained.

This separation allows for containers that are fully "open" by implementing
both interfaces on the same class, *and* for containers that are "closed" in
the sense that the services are encapsulated but not publicly modifiable.

### Why _IocContainer_ "Factory" and not _IocContainer_ "Builder" ?

"Builder" implies calling public setup methods, then a `build` method. "Factory"
implies only a `new` method. Even if there are multiple steps to the factory
process, they are not accessible as public methods.

### Why does _IocServicesProvider_ define `provideServices()` instead of `register()` ?

The method name `register()` is by far the majority choice for service provider
implementations. This standard breaks with that choice for consistency reasons.

Ioc-Interop opines that, unless the result is outright barbarous,
interface names and method names should mimic each other. Given a _Provider_
interface, its methods should `provide()`; given a `register()` method, its
interface should be a _Registry_ or _Registrant_. Further, as with the other
interfaces herein, the word "service" should be incorporated into the method
name. This leaves few choices:

- `IocServicesProvider::provideServices()` (closer to the majority class name)
- `IocServicesRegistrant::registerServices()` (closer to the majority method name)

Ioc-Interop opts in favor of honoring the class name, and modeling the method
name after it.

### What about property and setter injection?

TBD: Supported indirectly as extenders. Implementors may add support as desired,
perhaps in their [_IocServiceBuilder_][] implementations.

## What about "action", "method", or "invoker" injection?

TBD: "Action" or "method" injection involves using a container to call a method
(typically a controller action method) so that the container can injecting
services to the typehinted parameters on that method. Implementors are
encouraged to add their own implementations.

## Why an _IocServiceBuilder_ at all?

TBD: Is a place to collect all building logic: factory, autowiring, extenders.
Also a starting point for implementors to add arguments, setter injection,
property injection, etc. Could put these on IocServices but that expands the
API too much.

## Why _IocServiceBuilder_ and not _IocServiceDefinition_ ?

TBD: "Definition" is the only name used in the projects, when such functionality
is offered. Ioc-Interop breaks with this in favor of the more-formal design
pattern name "Builder".

## What about contextual or environmental binding?

TBD: When two different classes need different implementations of the same
interface. Relatively rare (only 2 projects). Another variation is that a class
needs different implementations in different environemt (e.g. web vs cli vs test).
Ioc-Interop finds little to standardize on as far as an API. Implementors are
encouraged to implement _IocParameterResolver_ attributes to note the specific
service to inject for a specific parameter.

## What about setter and property injection?

TBD: Property injection rare; setter injection less rare but introduces other
problems (when/how to resolve arguments?). Ioc-Interop favors constructor
injection as all projects support it. Implementors encouraged to add setter and
property injection on _IocServiceBuilder_ implementations. Consumers may add
service extenders for post-instantiation logic.

## What about lifetime scopes?

TBD: Ioc-Interop asserts that all services should be shared (aka "singleton" or
"request-scoped") services. [PHP-DI](https://github.com/PHP-DI/PHP-DI/blob/master/doc/scopes.md)
outlines the case. Consumers needing transient, prototype, or new-every-time
service instances are encouraged to depend on shared factory services instead,
or to build custom factories that call `newService()` when a new instance is
required.

* * *

[_Exception_]: https://php.net/Exception
[_IocContainer_]: #ioccontainer
[_IocContainerFactory_]: #ioccontainerfactory
[_IocServicesProvider_]: #iocservicesprovider
[_IocServices_]: #iocservices
[_IocServiceBuilder_]: #iocservicebuilder
[_IocClassResolver_]: #iocserviceresolver
[_IocParameterResolver_]: #iocparameterresolver
[_IocParametersResolver_]: #iocparametersresolver
[_IocThrowable_]: #iocthrowable
[_IocTypeAliases_]: #ioctypealiases
[_Throwable_]: https://php.net/Throwable
[BCP 14]: https://www.rfc-editor.org/info/bcp14
[PSR-11]: https://www.php-fig.org/psr/psr-11/
[README-RESEARCH.md]: ./README-RESEARCH.md
[RFC 2119]: https://datatracker.ietf.org/doc/html/rfc2119
[RFC 8174]: https://datatracker.ietf.org/doc/html/rfc8174
