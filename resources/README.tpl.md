# Ioc-Interop Standard Interface Package

This package provides interoperable interfaces for inversion-of-control
(IOC) container functionality. It reflects, refines, and reconciles the common
practices identified within [several pre-existing projects][README-RESEARCH.md].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [BCP 14][] ([RFC 2119][], [RFC 8174][]).

## Interfaces

This package defines the following interfaces:

- [_IocContainer_][] affords obtaining service instances by name, whether as shared instances or new unshared instances.

- [_IocServices_][] affords a registry  of service instances, factories, and aliases.

- [_IocProvider_][] affords provision of service instances, factories, and aliases to an [_IocServices_][] instance.

- [_IocContainerFactory_][] affords obtaining a new instance of [_IocContainer_][].

- [_IocThrowable_][] extends [_Throwable_][] to mark an [_Exception_][] as IOC-related.

- [_IocTypeAliases_][] defines PHPStan type aliases to aid static analysis.

{{= docs }}

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
service instance will be returned: `build` (2 projects), `create` (2), `get` (6),
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
