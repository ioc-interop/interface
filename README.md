# Ioc-Interop Standard Interface Package

This package provides interoperable interfaces for inversion-of-control
(IOC) container functionality. It reflects, refines, and reconciles the common
practices identified within [several pre-existing projects][README-RESEARCH.md].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [BCP 14][] ([RFC 2119][], [RFC 8174][]).

## Interfaces

This package defines the following interfaces:

- [_IocContainer_][] affords obtaining services by name, whether as shared instances or new unshared instances.

- [_IocServices_][] affords a registry of service instances, builders, and aliases.

- [_IocServicesProvider_][] affords provision of service instances, builders, and aliases to an [_IocServices_][] instance.

- [_IocServiceBuilder_][] affords building a service, including both instantiation and extended post-instantiation logic.

- [_IocServiceResolver_][] affords service instantiation.

- [_IocParameterResolver_][] affords obtaining an argument for a parameter.

- [_IocContainerFactory_][] affords obtaining a new instance of [_IocContainer_][].

- [_IocThrowable_][] extends [_Throwable_][] to mark an [_Exception_][] as IOC-related.

- [_IocTypeAliases_][] defines PHPStan type aliases to aid static analysis.

### _IocContainer_

The [_IocContainer_][] interface affords obtaining services by
name, whether as shared instances or new unshared instances.

#### _IocContainer_ Methods

- ```php
  public function hasService(ioc_service_name_string $serviceName) : bool;
  ```
    - Is the container capable of returning a shared instance of the
    service?

    - Directives:

        - Implementations MUST convert the `$serviceName` argument to its
          alias, if an alias exists for that `$serviceName`.

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
      mixed[] $serviceArgs = [],
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

        - **Service instantiation logic is not specified.** Implementations
          might use autowiring, configuration, builders, or some other means
          to create the service. The creation logic might be part of
          the container, or it might be part of some other subsystem.

### _IocServices_

The [_IocServices_][] interface affords a registry of service instances,
builders, and aliases.

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

    - Notes:

        - **TBD** May not have much meaning since getServiceBuilder() always
          returns an instance.

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

- ```php
  public function setServiceAlias(
      ioc_service_name_string $serviceName,
      ioc_service_name_string $serviceAlias,
  ) : void;
  ```
    - Sets the alias for one `$serviceName` to another service.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] if the
          `$serviceAlias` itself is aliased.

    - Notes:

        - **Only one level of aliasing is allowed.** An alias may not point
          to another alias; this is to prevent the possibillity of infinite
          recursion.

- ```php
  public function unsetServiceAlias(ioc_service_name_string $serviceName) : void;
  ```
    - Unsets the alias for the `$serviceName`.

### _IocServicesProvider_

The [_IocServicesProvider_][] interface affords provision of service
instances, builders, and aliases to an [_IocServices_][] instance.

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

The [_IocServiceBuilder_][] interface affords building a service,
including both instantiation and extended post-instantiation logic.

#### _IocServiceBuilder_ Methods

- ```php
  public function isServiceBuildable() : bool;
  ```
    - Is the service buildable?

    - Notes:

        - **TBD** Does it have a factory, or is it otherwise resolvable.

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
      mixed[] $serviceArgs = [],
  ) : object;
  ```
    - Invokes the service factory callable that instantiates the service.

    - Directives:

        - **TBD** If no factory, MUST throw.

        - **TBD** If $serviceArgs not empty, and factory cannot receive
          $serviceArgs as 2nd parameter, MUST throw.

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
      mixed[] $serviceArgs = [],
  ) : object;
  ```
    - Creates and returns a new instance of the service.

    - Notes:

        - **TBD** Instantiate (by factory or resolver) then apply extenders
          then return.

### _IocServiceResolver_

The [_IocServiceResolver_][] interface affords service instantiation.

#### _IocServiceResolver_ Methods

- ```php
  public function isServiceResolvable(
      ioc_service_name_string $serviceName,
  ) : bool;
  ```
    - Is the service resolvable?

    - Notes:

        - **TBD** Is `$serviceName` an existing and instantiable class?
          (Might use reflection.)

        - **TBD** Take the name as given, do not convert to alias.

- ```php
  public function resolveService(
      IocContainer $ioc,
      ioc_service_name_string $serviceName,
      mixed[] $serviceArgs = [],
  ) : ioc_service_object;
  ```
    - Given an [_IocContainer_][] to locate service dependencies, instantiates
    and returns the `$serviceName` with `$serviceArgs` constructor argument
    overrides.

    - Notes:

        - **TBD** Throw if `! isServiceResolvable($serviceName)`.

        - **TBD** Take the name as given, do not convert to alias.

        - **TBD** Autowiring, attributes, defaults, etc.

### _IocParameterResolver_

The [_IocParameterResolver_][] interface affords obtaining an argument for a
parameter.

- Notes:

    - **TBD** Have attribute implement this, then reflection logic can call
      `newInstance()->resolveParameter($ioc, $parameter)` to get back attribute value.
      E.g. `#[Inject(Foo::class)]` on a constructor parameter for a resolver
      to handle, or on a property for a builder to handle, etc.

#### _IocParameterResolver_ Methods

- ```php
  public function resolveParameter(
      IocContainer $ioc,
      ReflectionParameter $parameter,
  ) : mixed;
  ```
    - - Notes:

        - **TBD** Mixed, not object, as some attributes may be used for
          resolving non-object values, e.g. by pulling a container service
          and returning a value from it.

### _IocContainerFactory_

The [_IocContainerFactory_][] interface affords obtaining a new instance of
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

The [_IocThrowable_][] interface extends [_Throwable_][] to mark an
[_Exception_][] as IOC-related. It adds no class members.

### _IocTypeAliases_

The [_IocTypeAliases_][] interface defines PHPStan type aliases
to aid static analysis.

- ```
  ioc_service_extender_callable callable(object,IocContainer):object
  ```
    - A `callable` for service post-instantiation logic; e.g. to set a
      property, call a setter or initializer method, decorate the service,
      etc.

- ```
  ioc_service_factory_callable callable(IocContainer):object
      |callable(IocContainer,mixed[]=):object
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
[_IocServiceResolver_]: #iocserviceresolver
[_IocParameterResolver_]: #iocparameterresolver
[_IocThrowable_]: #iocthrowable
[_IocTypeAliases_]: #ioctypealiases
[_Throwable_]: https://php.net/Throwable
[BCP 14]: https://www.rfc-editor.org/info/bcp14
[PSR-11]: https://www.php-fig.org/psr/psr-11/
[README-RESEARCH.md]: ./README-RESEARCH.md
[RFC 2119]: https://datatracker.ietf.org/doc/html/rfc2119
[RFC 8174]: https://datatracker.ietf.org/doc/html/rfc8174
