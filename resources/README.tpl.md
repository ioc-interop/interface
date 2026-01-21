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

{{= list }}

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
