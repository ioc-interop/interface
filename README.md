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

- [_IocInstanceFactory_][] affords instantiating a class.

- [_IocServices_][] affords a registry of service instances, definitions, and aliases.

- [_IocProvider_][] affords provision of service instances, definitions, and aliases to an [_IocServices_][] instance.

- [_IocDefinition_][] affords building a service instance, including both instantiation logic and extended post-instantiation logic.

- [_IocResolver_][] affords resolving a class name to a new instance of that class.

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

    - **Keep the container itself as a service.** This allows consumer
      factories, builders, and locators to depend on the container. It may be
      easiest to do so as part of `__construct()`.

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

            - the `$serviceName` exists as an instantiable class.

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

        - **Create a new service instance if necessary.** In practice, this
          likely means calling `getDefinition($serviceName)->buildInstance()`
          and then retaining that instance for later retrieval.

### _IocInstanceFactory_

[_IocInstanceFactory_][] affords instantiating a class.

- Notes:

    - **The instance factory does not "build" or "retain" a new instance.**
      It does not specify applying any post-instantiation logic, as with
      [_IocDefinition_][]. Likewise, it does not "retain" the new
      instance as with [_IocServices_]. It only instantiates and returns.

    - **The instance factory is not a resolver.** However, implementations
      are likely to compose an [_IocResolver_][] and [_IocContainer_][] to
      support instantiation logic.

#### _IocInstanceFactory_ Methods

- ```php
  public function newInstance(
      string $class,
      mixed[] $arguments = [],
  ) : ($class is class-string<T> ? T
  ```
    - Returns a new instance of the `$class` with `$arguments` constructor
    argument overrides.

### _IocServices_

[_IocServices_][] affords a registry of service instances, definitions, and
aliases.

- Directives:

    - Implementations MUST set an instance of an [_IocResovler_[] using a
      $serviceName` of `IocResolver::class`.

- Notes:

    - **"Prime" the services with a resolver.** Because of the necessarily
      circular relationship regarding service resolution, implementations
      will need access to a pre-created [_IocResolver_][]. It may be easiest
      to do so as part of `__construct()`.

#### _IocServices_ Methods

- ```php
  public function hasInstance(ioc_service_name_string $serviceName) : bool;
  ```
    - Has a shared instance of the `$serviceName` been set?

- ```php
  public function getInstance(
      ioc_service_name_string $serviceName,
  ) : ioc_service_object;
  ```
    - Returns the shared instance of the `$serviceName`.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] if a shared instance
          of the `$serviceName` is not available.

- ```php
  public function setInstance(
      ioc_service_name_string $serviceName,
      ioc_service_object $instance,
  ) : void;
  ```
    - Sets the shared instance of the `$serviceName`.

- ```php
  public function unsetInstance(ioc_service_name_string $serviceName) : void;
  ```
    - Unsets the shared instance of the `$serviceName`.

- ```php
  public function hasDefinition(ioc_service_name_string $serviceName) : bool;
  ```
    - Has an [_IocDefinition_][] for the `$serviceName` been set?

- ```php
  public function getDefinition(
      ioc_service_name_string $serviceName,
  ) : IocDefinition;
  ```
    - Returns the [_IocDefinition_][] for the `$serviceName`, instantiating
    it if needed.

    - Notes:

        - **Create a new definition if necessary.** In practice, this
          likely means calling `newDefinition($serviceName)` and then
          retaining that instance for later retrieval.

- ```php
  public function newDefinition(
      ioc_service_name_string $serviceName,
  ) : IocDefinition;
  ```
    - Returns a new [_IocDefinition_][] for the `$serviceName`.

- ```php
  public function setDefinition(
      ioc_service_name_string $serviceName,
      IocDefinition $definition,
  ) : void;
  ```
    - Sets the [_IocDefinition_][] for the `$serviceName`.

- ```php
  public function unsetDefinition(ioc_service_name_string $serviceName) : void;
  ```
    - Unsets the [_IocDefinition_][] for the `$serviceName`.

- ```php
  public function hasAlias(ioc_service_name_string $serviceName) : bool;
  ```
    - Has an alias for the `$serviceName` been set?

- ```php
  public function getAlias(
      ioc_service_name_string $serviceName,
  ) : ioc_service_name_string;
  ```
    - Returns the alias for the `$serviceName`.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] an alias for the
          `$serviceName` is not available.

        - Implementations MUST return the final alias in the alias chain
          for the `$serviceName`.

    - Notes:

        - **Chained aliases are allowed.** That is, one alias can lead to
          another, and that one to yet another, and so on. This means
          implementations will have to track through those aliases to arrive
          at a final or terminal alias for the `$serviceName`.

- ```php
  public function setAlias(
      ioc_service_name_string $serviceName,
      ioc_service_name_string $alias,
  ) : void;
  ```
    - Sets the alias for one `$serviceName` to another service.

    - Directives:

        - Implementations MUST attempt to detect if adding the `$alias` would
          result in an infinite alias cycle; on detection, implementations
          MUST throw [_IocThrowable_][].

    - Notes:

        - **Chained aliases are allowed.** That is, one alias can lead to
          another, and that one to yet another, and so on. To prevent an
          infinite loop, implementations will have to track through the
          aliases to find if the `$alias` would end up back at itself.

- ```php
  public function unsetAlias(ioc_service_name_string $serviceName) : void;
  ```
    - Unsets the alias for the `$serviceName`.

### _IocProvider_

[_IocProvider_][] affords provision of service instances, definitions,
and aliases to an [_IocServices_][] instance.

#### _IocProvider_ Methods

- ```php
  public function provide(IocServices $services) : void;
  ```
    - Provides service instances, definitions, and aliases to the `$services`.

    - Notes:

        - **Provision includes a wide range of activity.** The implementation
          can set, unset, replace, modify, etc. the instances, definitions,
          and aliases in the `$services`.

### _IocDefinition_

[_IocDefinition_][] affords building a service instance, including both
instantiation logic and extended post-instantiation logic.

#### _IocDefinition_ Methods

- ```php
  public function hasFactory() : bool;
  ```
    - Is there a factory that instantiates the service?

- ```php
  public function getFactory() : ioc_service_factory_callable;
  ```
    - Returns the factory that instantiates the service.

    - Directives:

        - Implementations MUST throw [_IocThrowable_][] if there is no
          factory for the service.

- ```php
  public function setFactory(callable $factory) : self;
  ```
    - Sets the factory that instantiates the service.

    - Notes:

        - **The `callable` type allows for a wide range of implementations.**
          Cf. the <https://php.net/callable> documentation for more.

- ```php
  public function unsetFactory() : $this;
  ```
    - Unsets the factory that instantiates the service.

- ```php
  public function hasExtenders() : bool;
  ```
    - Are there any post-instantiation extenders for the service?

- ```php
  public function getExtenders() : ioc_service_extender_callable[];
  ```
    - Returns the post-instantiation extenders for the service.

- ```php
  public function setExtenders(
      ioc_service_extender_callable[] $extenders,
  ) : $this;
  ```
    - Sets all post-instantiation extenders for the service.

- ```php
  public function unsetExtenders() : $this;
  ```
    - Unsets all post-instantiation extenders for the service.

- ```php
  public function addExtender(ioc_service_extender_callable $extender) : $this;
  ```
    - Adds a single post-instantiation extender for the service.

    - Notes:

        - **The `callable` type allows for a wide range of implementations.**
          Cf. the <https://php.net/callable> documentation for more.

- ```php
  public function buildInstance(IocContainer $ioc) : object;
  ```
    - Builds a new instance of the service.

    - Directives:

        - Implementations MUST instantiate the service with the defined
          factory if one is set; otherwise, implmentations SHOULD instantiate
          the service using an [_IocResolver_][] implementation.

        - Implementation MUST apply all defined extenders to the
          newly-instantiated service.

### _IocResolver_

[_IocResolver_][] affords resolving a class name to a new instance of
that class.

#### _IocResolver_ Methods

- ```php
  public function isResolvable(string $class) : bool;
  ```
    - Does the `$class` exist, and is it instantiable?

- ```php
  public function resolve(
      IocContainer $ioc,
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
      IocContainer $ioc,
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
      IocContainer $ioc,
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
  ioc_service_factory_callable callable(IocContainer):object
  ```
    - A `callable` for service instantiation logic.

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
