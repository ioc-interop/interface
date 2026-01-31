# Ioc-Interop Standard Interface Package

Ioc-Interop provides an interoperable package of standard interfaces for
inversion-of-control (IOC) service container functionality. It reflects,
refines, and reconciles the common practices identified within
[several pre-existing projects][README-RESEARCH.md].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [BCP 14][] ([RFC 2119][], [RFC 8174][]).

## Interfaces

This package defines the following interfaces:

- [_IocContainer_][] affords obtaining services by name.

- [_IocContainerFactory_][] affords obtaining a new instance of [_IocContainer_][].

- [_IocThrowable_][] extends [_Throwable_][] to mark an [_Exception_][] as IOC-related. It adds no class members.

- [_IocTypeAliases_][] defines PHPStan type aliases to aid static analysis.

### _IocContainer_

[_IocContainer_][] affords obtaining services by name.

- Notes:

    - **This interface does not afford service management.** The container
      will need to obtain services somehow, e.g. from a [Service-Interop][]
      implementation.

#### _IocContainer_ Methods

- ```php
  public function hasService(ioc_service_name_string $serviceName) : bool;
  ```
    - Is the container able to return an instance of the `$serviceName`?

    - Notes:

        - **The logic for this method is expressly unspecified.** The ability
          check may accomplished by querying a service management subsystem,
          or by some other means.

- ```php
  public function getService(
      ioc_service_name_string $serviceName,
  ) : ioc_service_object;
  ```
    - Returns an instance of the `$serviceName`.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] if the container
          cannot return an instance of the `$serviceName`.

    - Notes:

        - **The logic for this method is expressly unspecified.** Retrieval
          may be accomplished via a service management subsystem, or by some
          other means.

        - **The returned instance may be new or shared.** The retrieval
          logic defines the service lifetime, not the container (per se) and
          not the caller requesting the service.

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

    - **Reference implementations** may be found at
      <https://github.com/ioc-interop/impl>.

## Q & A

### How is Ioc-Interop different from PSR-11?

[PSR-11][] is an earlier recommendation that offers an interface to `get`
items from a container, and to see if that container `has` a particular item.

Ioc-Interop is functionally almost identical to PSR-11. However, Ioc-Interop
is intended to contain only services (`object`). PSR-11 is intended to contain
anything (`mixed`).

Ioc-Interop also offers an [_IocContainerFactory_][] interface, whereas PSR-11
offers none.

### Is Ioc-Interop compatible with PSR-11?

No, in the sense that the method names, signatures, and intents are different.

Yes, in the sense that both may be implemented on the same class; the method
names are different, and so are non-conflicting.

### Why does Ioc-Interop not afford service management?

Ioc-Interop is focused on the concerns around *obtaining* and *consuming*
services.  The affordances for *managing* and *producing* services are a set of separate concerns.

Earlier drafts of Ioc-Interop were much more expansive, including a resolver
subsystem and a service management subsystem. These have been extracted to
separate standards, each of which is dependent on Ioc-Interop:

- [Service-Interop][]
- [Resolver-interop][]

This separation helps to maintain a boundary between the needs of service
consumers (afforded by Ioc-Interop) and service producers (afforded by
[Service-Interop][] and [Resolver-Interop][]).

Note that Ioc-Interop is independent of [Service-Interop][] and
[Resolver-Interop][]. Ioc-Interop implementations can use them, or avoid them,
as implementors see fit.

### Is [_IocContainer_][] for Dependency Injection or is it a Service Locator?

[_IocContainer_][] acts a Service Locator only when it is used as a dependency
in order to retrieve other dependencies from it.

### Why does [_IocContainer_][] disallow non-object values?

[_IocContainer_][] is explicitly a *service* container, not a general config
container for [scalar][] or [array][] values. (Ioc-Interop questions what it
means, or if it is possible, to get a "shared" scalar or array that works the
same as a "shared" object.) Limiting services to objects helps maintain
consistent expectations regarding service types and behavior.

Ioc-Interop recognizes that implementors and consumers often want to make config
values easily available. Instead of storing config values directly inside a
container, Ioc-Interop encourages the use of one or more config services or
value objects to make those values available.

### Why does [_IocContainer_][] define `getService()` and not just `get()` ?

The vast majority of researched projects, whether PSR-11 conforming or not, use
the method name `get()`. Contra the research, Ioc-Interop asserts that `get()`
is too generic, and that the method name should hint at what is being gotten;
thus, `getService()`.

* * *

[_Exception_]: https://php.net/Exception
[_IocContainer_]: #ioccontainer
[_IocContainerFactory_]: #ioccontainerfactory
[_IocThrowable_]: #iocthrowable
[_IocTypeAliases_]: #ioctypealiases
[_Throwable_]: https://php.net/Throwable
[BCP 14]: https://www.rfc-editor.org/info/bcp14
[PSR-11]: https://www.php-fig.org/psr/psr-11/
[README-RESEARCH.md]: ./README-RESEARCH.md
[Resolver-Interop]: https://github.com/resolver-interop/interface
[RFC 2119]: https://datatracker.ietf.org/doc/html/rfc2119
[RFC 8174]: https://datatracker.ietf.org/doc/html/rfc8174
[Service-Interop]: https://github.com/service-interop/interface
[scalar]: https://www.php.net/manual/en/language.types.type-system.php#language.types.type-system.atomic.scalar
[array]: https://www.php.net/manual/en/language.types.array.php
[resource]: https://www.php.net/manual/en/language.types.resource.php
