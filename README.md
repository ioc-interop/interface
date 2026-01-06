# Ioc-Interop Standard Interface Package

This package provides interoperable interfaces for inversion-of-control
(IOC) container functionality. It reflects, refines, and reconciles the common
practices identified within [several pre-existing projects][README-RESEARCH.md].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [BCP 14][] ([RFC 2119][], [RFC 8174][]).

## Interfaces

This package defines the following interfaces:

- [_IocContainer_][] affords obtaining service instances by name, whether as shared instances or new unshared instances.

- [_IocServices_][] affords a registry of service instances, factories, and aliases.

- [_IocProvider_][] affords provision of service instances, factories, and aliases to an [_IocServices_][] instance.

- [_IocContainerFactory_][] affords obtaining a new instance of [_IocContainer_][].

- [_IocThrowable_][] extends [_Throwable_][] to mark an [_Exception_][] as IOC-related.

- [_IocTypeAliases_][] defines PHPStan type aliases to aid static analysis.

### _IocContainer_

The [_IocContainer_][] interface affords obtaining service instances by
name, whether as shared instances or new unshared instances.

- Directives:

    - Implementations MUST convert each `$serviceName` argument to its alias, if
      an alias exists for that `$serviceName`.

#### _IocContainer_ Methods

- ```php
  public function hasService(ioc_service_name_string $serviceName) : bool;
  ```
    - Is the container capable of returning a shared instance of the
    `$serviceName`?

- ```php
  public function getService(
      ioc_service_name_string $serviceName,
  ) : ioc_service_object;
  ```
    - Returns a shared instance of the `$serviceName`, instantiating it if
    necessary.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] if the
          container cannot return a shared instance of the `$serviceName`.

        - Implementations MUST return the same instance of the `$serviceName`
          service each time this method is called.

    - Notes:

        - **Create and retain a new instance if necessary.** In practice,
          this likely means calling `newService($serviceName)` and holding
          onto the newly-created instance for later calls to
          `getService($serviceName)`.

- ```php
  public function newService(
      ioc_service_name_string $serviceName,
  ) : ioc_service_object;
  ```
    - Returns a new instance of the `$serviceName`.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] if the
          container cannot return a new instance of the `$serviceName`.

        - Implementations MUST return a different instance of the
          `$serviceName` each time this method is called.

    - Notes:

        - **Service instantiation logic is not specified.** Implementations
          might use autowiring, configuration, factories, or some other means
          to create the service instance. The creation logic might be part of
          the container, or it might be part of some other subsystem.

### _IocServices_

The [_IocServices_][] interface affords a registry of service instances,
factories, and aliases.

- Directives:

    - Implementations MUST NOT convert any `$serviceName` argument to its alias.

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
  public function hasServiceFactory(ioc_service_name_string $serviceName) : bool;
  ```
    - Has a factory for the `$serviceName` been set?

- ```php
  public function getServiceFactory(
      ioc_service_name_string $serviceName,
  ) : ioc_service_factory_callable;
  ```
    - Returns the factory for the `$serviceName`.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] a factory for the
          `$serviceName` is not available.

- ```php
  public function setServiceFactory(
      ioc_service_name_string $serviceName,
      ioc_service_factory_callable $serviceFactory,
  ) : void;
  ```
    - Sets the factory for the `$serviceName`.

    - Notes:

        - **The `callable` type allows for a wide range of implementations.**
          Cf. the <https://php.net/callable> documentation for more.

- ```php
  public function unsetServiceFactory(
      ioc_service_name_string $serviceName,
  ) : void;
  ```
    - Unsets the factory for the `$serviceName`.

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

### _IocProvider_

The [_IocProvider_][] interface affords provision of service instances,
factories, and aliases to an [_IocServices_][] instance.

#### _IocProvider_ Methods

- ```php
  public function provideServices(IocServices $services) : void;
  ```
    - Provides service instances, factories, and aliases to the `$services`.

    - Notes:

        - **Provision includes a wide range of activity.** The implementation
          can set, unset, replace, etc. the instances, factories, and aliases
          in the `$services`.

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
  ioc_service_factory_callable callable(IocContainer):object
  ```
    - A `callable` to create and return a new instance of a service.

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
  factories, and aliases. PSR-11 offers no such interface.

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

### Why does _IocContainer_ define `newService()` instead of `make()`, `create()`, or `build()` ?

The researched projects use several different terms to indicate that a new
service instance will be returned: `build` (2 projects), `create` (3), `get` (6),
`make` (3), and `new` (2).

The terms `get` and `make` are ambiguous in the researched projects. They might:

- create a new service instance every time;

- return a shared service instance every time;

- create a new service instance the first time and return that same instance
  every time thereafter; or,

- do some combination of the above, depending how the service was defined.

The terms `build` and `create` are less-ambiguous, but are much less common.

In comparison, `newService()` is easily disambiguated from `getService()`.
Ioc-Interop stipulates that former always returns a new instance, and the latter
always returns a shared instance (after creating it if necessary).

### Why is _IocContainer_ separate from _IocServices_?

Whereas _IocContainer_ is for *obtaining* instances, _IocServices_ is for
*registering* the instances, factories, and aliases involved in producing the
services to be obtained.

This separation allows for containers that are fully "open" by implementing
both interfaces on the same class, *and* for containers that are "closed" in
the sense that the services are encapsulated but not publicly modifiable.

### Why _IocContainer_ "Factory" and not _IocContainer_ "Builder" ?

"Builder" implies calling public setup methods, then a `build` method. "Factory"
implies only a `new` method. Even if there are multiple steps to the factory
process, they are not accessible as public methods.

### Why does _IocProvider_ define `provideServices()` instead of `register()` ?

The method name `register()` is by far the majority choice for service provider
implementations. This standard breaks with that choice for consistency reasons.

Ioc-Interop opines that, unless the result is outright barbarous,
interface names and method names should mimic each other. Given a _Provider_
interface, its methods should `provide()`; given a `register()` method, its
interface should be a _Registrant_. Further, as with the other interfaces herein,
the word "service" should be incorporated into the method name. This leaves two
choices:

- `IocProvider::provideServices()` (closer to the majority class name)
- `IocRegistrant::registerServices()` (closer to the majority method name)

Ioc-Interop opts in favor of honoring the class name, and modeling the method
name after it.

* * *

[_Exception_]: https://php.net/Exception
[_IocContainer_]: #ioccontainer
[_IocContainerFactory_]: #ioccontainerfactory
[_IocProvider_]: #iocprovider
[_IocServices_]: #iocservices
[_IocThrowable_]: #iocthrowable
[_IocTypeAliases_]: #ioctypealiases
[_Throwable_]: https://php.net/Throwable
[BCP 14]: https://www.rfc-editor.org/info/bcp14
[PSR-11]: https://www.php-fig.org/psr/psr-11/
[README-RESEARCH.md]: ./README-RESEARCH.md
[RFC 2119]: https://datatracker.ietf.org/doc/html/rfc2119
[RFC 8174]: https://datatracker.ietf.org/doc/html/rfc8174
