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

### General

#### How is Ioc-Interop different from PSR-11?

[PSR-11][] is an earlier recommendation that offers an interface to `get`
items from a container, and to see if that container `has` a particular item.
The Ioc-Interop standard is more expansive.

- Ioc-Interop is intended to contain only services (`object`). PSR-11
  is intended to contain anything (`mixed`).

- Ioc-Interop and PSR-11 each offer a method to "get" a service. Whereas PSR-11
  does not specify the scope or lifetime of the service, Ioc-Interop specifies
  it as "shared" (aka "singleton" or "request-scoped").

- Ioc-Interop and PSR-11 each offer a method to see if the container "has" a
  service. Whereas PSR-11 does not specify what "has" means, Ioc-Interop defines
  it to mean that the container has access to a shared instance of the service,
  or that it has access to the logic needed to build such an instance.

- Ioc-Interop offers an [_IocInstanceFactory_][] to explicitly create new
  instances. PSR-11 offers no similar interface.

- Ioc-Interop offers an [_IocServices_][] interface to set/get/has/unset service
  instances, definitions, and aliases, separately from the container itself. PSR-11
  offers no such interface.

- Ioc-Interop offers [_IocResolver_][], [_IocParametersResolver_][], and
  [_IocParameterResolver_][] interfaces. PSR-11 offers none.

- Ioc-Interop offers an [_IocContainerFactory_][] interface. PSR-11 offers none.

- Ioc-Interop defines one [_IocThrowable_][] interface. PSR-11 defines two
  exception marker iterfaces.

#### Is Ioc-Interop compatible with PSR-11?

No, in the sense that the method names, signatures, and intents are different.

Yes, in the sense that both may be implemented on the same class; the method
names are different, and so are non-conflicting.

### Container, Services, and InstanceFactory

#### Is [_IocContainer_][] a Dependency Injection system or a Service Locator?

[_IocContainer_][] acts a Service Locator only when it is used as a dependency
in order to retrieve other dependencies from it.

#### Why does [_IocContainer_][] disallow non-object values?

Some container systems allow any kid of value: null, scalar, array, resource,
and object. However, Ioc-Interop questions what it means, or if it is possible,
to get a "shared" scalar or array value that works the same way as a "shared"
object. To maintain consistent behavior expectations, Ioc-Interop limits
services to objects.

Implementors and consumers often want to keep configuration values directly
inside a container. Ioc-Interop encourages the use of one or more configuration
services instead.

#### Why is [_IocContainer_][] separate from [_IocServices_][]?

Whereas [_IocContainer_][] is for *obtaining* instances, [_IocServices_][] is for
*registering* the instances, definitions, and aliases involved in producing the
services to be obtained.

This separation allows for containers that are fully "open" by implementing
both interfaces on the same class, *and* for containers that are "closed" in
the sense that the services are encapsulated but not publicly modifiable.

#### Why a separate [_IocInstanceFactory_][] ?

[_IocContainer_][] provides access to shared service instances, but not to new
unshared instances. Even to, it is often useful to have access to new-instance
functionality through an underlying [_IocResolver_][], such as when creating
type-restricted factories or custom builders.

The [_IocInstanceFactory_][] provides that functionality separately so as to
preserve the [_IocContainer_][] concentration on *shared* services. Implementors
wishing to combine both shared service and new instance functionality may
implement both interfaces on the same class.


### Why does [_IocInstanceFactory_][] define `newInstance()` instead of `make()`, `create()`, or `build()` ?

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

In comparison, `newInstance()` is easily disambiguated from `getService()`.
Ioc-Interop stipulates that former always returns a new instance, and the latter
always returns a shared instance (after creating it if necessary).

### Service Definitions

#### Why an [_IocDefinition_][] at all?

Whereas it's possible to set a pre-created service instance into a container,
very often it's preferred to set a factory to create that instance only when
needed. Further, sometimes that new instance may need to be modified after
instantiation with custom extender logic. Finally, the factory might be more
generalized instead of service-specific, as with autowiring resolvers.

Some projects place all that functionality directly on the container. However,
that results in a very large API surface area. Other projects collect that
functionality onto a "builder" object, typically called a "definition."

Ioc-Interop adopts the latter approach, not only because it separates the
concerns of building from retrieval, but also because it gives implementors
a natural extension point for custom building behaviors.

#### Why does [_IocDefinition_][] not support property or setter injection?

Some projects functionality support the ability to set properties on the
newly-instantiated service. Others support the ability to call "setter" or other
methods on the newly-instantiated service.

However, the APIs around this kind of functionality are different enough from
each other that it is difficult to discern a standard. In addition, is can be
difficult to lazily acquire the values or arguments to property-inject or
setter-inject; the different projects support these in very different ways.

As such, [_IocDefinition_][] does not directly support property injection,
setter injection, and so on. Implementors are encouraged to add support as
desired to their implementations.

However, note that [_IocDefinition_][] does support alternative injection
strategies *indirectly* via extenders. For example:

```php
$fooDefinition->setExtender(fn (IocContainer $ioc, Foo $foo) : Foo) {
    // property injection
    $foo->bar = 'bar';

    // setter injection
    $foo->setBaz('baz');

    // done
    return $foo;
});
```

#### Why does [_IocDefinition_][] not support contextual or environmental binding?

Sometimes two different classes need different implementations of the same
interface. Functionality to specify different services to inject on the same
typehints is relatively rare; only 2 of the researched projects support it.

Another variation on this is when a class needs different implementations in
different environments (e.g. "web" vs "cli" vs "test"). This too is relatively
rare among the researched projects.

As such, Ioc-Interop finds little to standardize on as far as an API.
Implementors are encouraged to implement _IocParameterResolver_ attributes
to note the specific service to inject for a specific parameter.

#### Why does [_IocDefinition_][] not support lifetime scopes?

Some container implementations offer service-specific "lifetimes" or "scopes" to
determine when a service is created and destroyed. For example:

- `singleton` for services that are retained across requests;

- `shared` or `request-scoped` for services that is retained only for the
  current request;

- `transient` or `prototype` for a service that is created anew each time and
  never retained.

These terms are not common across the researched projects; some call a "shared"
service a "singleton", others may not provide cross-request lifetimes, and so
on.

Ioc-Interop asserts that in a typical PHP environment, all services should be
shared services that are retained for the duration of the current request. The
[PHP-DI](https://github.com/PHP-DI/PHP-DI/blob/master/doc/scopes.md)
project outlines the case. Essentially, scopes create the expectation that
values can be recalculated on demand, when in fact they may not be. Further,
scopes make the container act as implicitly as a factory.

Consumers needing transient, prototype, or new-every-time service instances are
encouraged to explicitly depend instead on shared factory services, perhaps ones
that extend or encapsulate an [_IocInstanceFactory_][].

Implementors desiring cross-request services are encouraged to extend their
[_IocServices_][] implementations with the necessary logic.

### Other

#### Why [_IocContainerFactory_][] and not a _IocContainerBuilder_ ?

A "builder" implies calling public setup methods to define a build process,
then a `build` method to execute that process and instantiate the object. A
"factory" implies only a `new` method, with no other public setup methods to
define a build process.

As there is no "build" process for a container, other than perhaps to provide
services to that container, that makes the creation pattern a factory.

#### Why does [_IocProvider_][] define `provide()` instead of `register()` ?

The method name `register()` is by far the majority choice for service provider
implementations. This standard breaks with that choice for consistency reasons.

Ioc-Interop opines that, unless the result is outright barbarous,
interface names and method names should mimic each other. Given a _Provider_
interface, its methods should `provide()`; given a `register()` method, its
interface should be a _Registry_ or _Registrant_. Further, as with the other
interfaces herein, the word "service" should be incorporated into the method
name. This leaves few choices:

- `IocProvider::provide()` (closer to the majority class name)
- `IocRegistrant::register()` (closer to the majority method name)

Ioc-Interop opts in favor of honoring the class name, and modeling the method
name after it.

#### What about "action" or "invoker" injection?

TBD: "Action" or "method" injection involves using a container to call a method
(typically a controller action method) so that the container can inject services
to the typehinted parameters on that method, then get back the result.
Implementors are encouraged to add their own implementations.

* * *

[_Exception_]: https://php.net/Exception
[_IocResolver_]: #iocserviceresolver
[_IocContainer_]: #ioccontainer
[_IocContainerFactory_]: #ioccontainerfactory
[_IocInstanceFactory_]: #iocinstancefactory
[_IocParameterResolver_]: #iocparameterresolver
[_IocParametersResolver_]: #iocparametersresolver
[_IocDefinition_]: #iocservicebuilder
[_IocServices_]: #iocservices
[_IocProvider_]: #iocservicesprovider
[_IocThrowable_]: #iocthrowable
[_IocTypeAliases_]: #ioctypealiases
[_Throwable_]: https://php.net/Throwable
[BCP 14]: https://www.rfc-editor.org/info/bcp14
[PSR-11]: https://www.php-fig.org/psr/psr-11/
[README-RESEARCH.md]: ./README-RESEARCH.md
[RFC 2119]: https://datatracker.ietf.org/doc/html/rfc2119
[RFC 8174]: https://datatracker.ietf.org/doc/html/rfc8174
