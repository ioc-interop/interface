# Research

Ioc-Interop is based on research into the following projects that provide
inversion-of-control containers:

- [aura/di](https://github.com/auraphp/Aura.Di) (aura)
- [flightphp/container](https://github.com/flightphp/container) (flightphp)
- [ghostwriter/container](https://github.com/ghostwriter/container) (ghostwriter)
- [illuminate/container](https://github.com/illuminate/container) (illuminate)
- [joomla/di](https://github.com/joomla-framework/di) (joomla)
- [laminas/laminas-di](https://github.com/laminas/laminas-di) (laminas)
- [league/container](https://github.com/thephpleague/container) (league)
- [nette/di](https://github.com/nette/di) (netter)
- [pimple/pimple](https://github.com/silexphp/Pimple) (pimple)
- [Phalcon 4.x](https://github.com/phalcon/cphalcon/) (phalcon)
- [php-di/php-di](https://github.com/PHP-DI/PHP-DI) (phpdi)
- [ray/di](https://github.com/ray-di/Ray.Di) (ray)
- [rdlowrey/auryn](https://github.com/rdlowrey/auryn) (rdlowrey)
- [symfony/dependency-injection](https://github.com/symfony/dependency-injection) (symfony)
- [tempest/container](https://packagist.org/packages/tempest/container) (tempest)
- [yiisoft/di](https://packagist.org/packages/yiisoft/di) (yii)

The following projects were considered but eventually excluded because they use
external container systems:

- Cake v5 -- uses League
- [Mezzio](https://github.com/mezzio/) -- uses other PSR-11 containers
- Slim -- v3 used Pimple, Slim v4 et al. use any PSR-11 container

The following projects were considered but eventually excluded because they had
no obvious or discernible container system:

- [Code Igniter](https://github.com/bcit-ci/)
- [Horde](https://github.com/horde/)
- [Klein](https://github.com/klein/)
- [Lithium](https://github.com/UnionOfRAD/)
- [YAF](https://www.php.net/yaf/)
- [MediaWiki](https://github.com/wikimedia/mediawiki)


## PSR-11 Implementations

- "Yes": is a `get(string $id) : mixed` implementation
- "Opt": offers an `get(string $id) : mixed` implementation as an option
- "Ish": is a `get(string $id) : object` (not `mixed`) implementation
- "No": not implemented

|             | Yes | Opt | Ish | No |
| ----------- | --- | --- | --- | -- |
| aura        | x   |     |     |    |
| ghostwriter |     |     |     | x  |
| flightphp   |     |     | x   |    |
| joomla      | x   |     |     |    |
| illuminate  | x   |     |     |    |
| laminas     |     |     | x   |    |
| league      | x   |     |     |    |
| nette       |     |     |     | x  |
| phalcon     |     | x   |     |    |
| phpdi       | x   |     |     |    |
| pimple      |     | x   |     |    |
| ray         |     |     |     | x  |
| rdlowrey    | x   |     |     |    |
| symfony     | x   |     |     |    |
| tempest     |     |     |     | x  |
| yii         | x   |     |     |    |

10 projects are conforming PSR-11 implementations; 6 are modified or non-implementations of PSR-11.

## Autowiring

The projects allow different autowiring modes:

- "Always": autowiring is always on.
- "Opt-Out": autowiring is on by default, but can be disabled (whether in toto or on a case-by-case basis).
- "Opt-In": autowiring is off by default, but can be enabled (whether in toto or on a case-by-case basis).
- "Never": not available.

|             | Always | Opt-Out | Opt-In | Never |
| ----------- | ------ | ------- | ------ | ----- |
| aura        |        |         | x      |       |
| flightphp   | x      |         |        |       |
| ghostwriter | x      |         |        |       |
| illuminate  | x      |         |        |       |
| joomla      | x      |         |        |       |
| laminas     | x      |         |        |       |
| league      |        | x       |        |       |
| nette       |        | x       |        |       |
| phalcon     |        |         |        | x     |
| phpdi       |        | x       |        |       |
| pimple      |        |         |        | x     |
| ray         | x      |         |        |       |
| rdlowrey    | x      |         |        |       |
| symfony     |        | x       |        |       |
| tempest     |        |         | x      |       |
| yii         | x      |         |        |       |


## Default: Shared or New?

When getting a service from the container, is the instance ...

- shared (aka a "singleton" instance) by default; or,
- is it a newly-created instance by default?

|             | Shared | New |
| ----------- | ------ | --- |
| aura        | x      |     |
| flightphp   |        | x   |
| ghostwriter | x      |     |
| illuminate  |        | x   |
| joomla      |        | x   |
| laminas     | x      |     |
| league      | (1)    | (1) |
| nette       | x      |     |
| phalcon     |        | x   |
| phpdi       | x      |     |
| pimple      | x      |     |
| ray         | x      |     |
| rdlowrey    |        | x   |
| symfony     | x      |     |
| tempest     |        | x   |
| yii         | x      |     |

1. `league` can switch defaults via `defaultToShared(bool $shared = true)`

N.b.: The projects that are new-by-default allow marking individual services as shared.

9 projects are shared-by-default; 6 are new-by-default; 1 allows either.

## Has a service

The projects afford checking to see if the container "has" a service, but the meaning is slightly different between them all.

|             | Signature                                                           | Meaning                                                   |
| ----------- | ------------------------------------------------------------------- | --------------------------------------------------------- |
| aura        | `has(string $id) : bool`                                            | "Does a service definition exist?"                        |
| flightphp   | `has(string $id) : bool`                                            | "Is an entry key set for $id?"                            |
| ghostwriter | `has(string $id) : bool`                                            | "Does get() return a service?"                            |
| illuminate  | `has(string $id) : bool`                                            | "Has $id been bound?"                                     |
| joomla      | `has(string $resourceName) : bool`                                  | "Is a resource key set here or in parent container?"      |
| laminas     | `has(string $name) : bool`                                          | "Has an instance, or can injector create one?"            |
| league      | `has(string $id) : bool`                                            | "Has a definition, has a tag, has provided, has delegate" |
| nette       | `hasService(string $name) : bool`                                   | "Has an instance or factory"                              |
| phalcon     | `has(string $name) : bool`                                          | "Has a services key"                                      |
| phpdi       | `has(string $id) : bool`                                            | "Has an instance, or a resolvable definition"             |
| pimple      | `offsetExists(string $id) : bool`                                   | "Is a key set for the $id?"                               |
| ray         | -                                                                   | -                                                         |
| rdlowrey    | -                                                                   | -                                                         |
| symfony     | `has(string $id) : bool`                                            | "Has an instance, or is mapped from a file or method"     |
| tempest     | `has(string $className, null\|string\|UnitEnum $tag = null) : bool` | "Has a definition or a singleton"                         |
| yii         | `has(string $id) : bool`                                            | "Has a definition or a tag"                               |


## Get a shared service instance

The projects use these method signatures to get a shared (aka "singleton") service from the container.

Note that the new-by-default containers will return a new instance if the
service was not defined as shared. That is, "get a shared instance" might return
a new instance, and you won't know from the call-site.

|             | Might be new |  Signature   |
| ----------- | ------------ | ------------ |
| aura        |              | `get(string $id) : object` |
| flightphp   | x (1)        | `get(string $id) : object` |
| ghostwriter |              | `get(string $id) : object` |
| illuminate  | x (2)        | `get(string $id) : ($id is class-string<TClass> ? TClass : mixed)` |
| joomla      | x (3)        | `get($resourceName) : mixed` |
| laminas     |              | `get(string $name) : object` |
| league      | x (4)        | `get(string $id) : mixed` |
| nette       |              | `getService(string $name) : object` |
| phalcon     |              | `getShared(string $name, $parameters = null) : object` |
| phpdi       |              | `get(string $id) : mixed` |
| pimple      | x (5)        | `offsetGet(string $id) : mixed` |
| ray         |              | `getInstance($interface, $name = Name::ANY)` |
| rdlowrey    | x (6)        | `make($name, array $args = array()) : mixed` |
| symfony     |              | `get(string $id, int $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE) : ?object` |
| tempest     | x (7)        | `get(string $className, null\|string\|UnitEnum $tag = null, mixed ...$params) : object` |
| yii         | x (8)        | `get(string $id) : ($id is class-string ? T : mixed)` |

1. `flightphp` will return a new instance unless the service was set as a `singleton()`.

2. `illuminate` will return a new instance unless the service was ...
    - registered as a shared service via `singleton($abstract, $concrete = null)`
    - set directly as a shared service via `instance($abstract, $instance)`
    - bound with `$shared = true`: `bind($abstract, $concrete = null, $shared = false)`

3. `joomla` will return a new instance unless the service was defined as shared.

4. `league` will return a new instance unless the service was set as shared.

5. `pimple` will return a new instance if the service was set as a `factory()`

6. `rdlowrey` will return a new instance unless the service was set as shared, in which case the `$args` are ignored.

7. `tempest` will return a new instance unless the service was set as shared.

8. `yii` will return a new instance if the service was set as a callable.

## Shared Service Types

|             | `object` | `mixed` |
| ----------- | -------- | ------- |
| aura        | x        |         |
| flightphp   | x        |         |
| ghostwriter | x        |         |
| illuminate  |          | x       |
| joomla      |          | x       |
| laminas     | x        |         |
| league      |          | x       |
| nette       | x        |         |
| phalcon     | x        |         |
| phpdi       |          | x       |
| pimple      |          | x       |
| ray         | x        |         |
| rdlowrey    | x        |         |
| symfony     | x (1)    |         |
| tempest     | x        |         |
| yii         |          | x       |

1. `symfony` is `?object`.

## Get a new service instance

The projects allow returning a newly-created (non-shared/non-singleton) service instance.

Many of them allow specifying constructor or setter arguments for the new instance.

Note that the new-by-default containers will return a shared instance if the
service was defined as shared. So "new instance" might return a shared instance,
and you won't know from the call-site.

### Arguments not accepted

|             | Might be shared | Signature                                             |
| ----------- | --------------- | ----------------------------------------------------- |
| aura        |                 |                                                       |
| flightphp   | x (1)           | `get(string $id) : object`                            |
| ghostwriter |                 |                                                       |
| illuminate  |                 |                                                       |
| joomla      |                 | `buildObject($resourceName)`                          |
| laminas     |                 |                                                       |
| league      |                 | `getNew(string $id) : mixed`                          |
| nette       |                 | `createService(string $name) : object`                |
| phalcon     |                 |                                                       |
| phpdi       |                 |                                                       |
| pimple      | x (6)           | `offsetGet($id) : mixed`                              |
| ray         |                 | `getInstance($interface, $name = Name::ANY) : object` |
| rdlowrey    |                 |                                                       |
| symfony     |                 |                                                       |
| tempest     |                 |                                                       |
| yii         | x (9)           | `get(string $id) : ($id is class-string ? T : mixed)` |

### Arguments accepted but not required

|             | Might be shared | Signature                                                                                |
| ----------- | --------------- | ---------------------------------------------------------------------------------------- |
| aura        |                 | `newInstance(string $class, array $mergeParams = [], array $mergeSetters = []) : object` |
| flightphp   |                 |                                                                                          |
| ghostwriter |                 | `build(string $id, array $arguments = []) : object`                                      |
| illuminate  | x (2)           | `make(string\|class-string<TClass>\|callable $abstract, array $parameters = []) : ($abstract is class-string<TClass> ? TClass : mixed)` |
| joomla      |                 |                                                                                          |
| laminas     |                 | (3)                                                                                      |
| league      |                 |                                                                                          |
| nette       |                 |                                                                                          |
| phalcon     | x (4)           | `get(string $name, $parameters = null) : object`                                         |
| phpdi       | x (5)           | `make(string $name, array $parameters = []) : mixed`                                     |
| pimple      | x (6)           |                                                                                          |
| ray         |                 | `getInstanceWithArgs(string $interface, array $params) : object`                         |
| rdlowrey    | x (7)           | `make($name, array $args = array()) : object`                                            |
| symfony     |                 |                                                                                          |
| tempest     | x (8)           | `get(string $className, null\|string\|UnitEnum $tag = null, mixed ...$params) : object`  |
| yii         | x (9)           |                                                                                          |

### Notes

1. `flightphp` returns a shared instance if the service was set as a `singleton()`.

2. `illuminate` returns a shared instance if the service was set as shared.

3. `laminas` _Injector_ offers `create(string $name, array $options = []) : object`.

4. `phalcon` returns a shared instance if it was set as shared.

5. `phpdi` returns a shared instance if the service is not a definition.

6. `pimple` lets you define an always-new service using `$pimple->offsetSet($id, $pimple->factory(function ($c) { /* ... */ })` -- then `offsetGet($id)` always returns a new instance of `$id`.

7. `rdlowrey` returns a shared instance if it has been set via `share()`, ignoring `$args`.

8. `tempest` returns a shared instance if it was set as a singleton.

9. `yii` returns a shared instance if it was not set as a callable.

Terminology:

|             | Build | Create | Get | Make | New |
| ----------- | ----- | ------ | --- | ---- | --- |
| aura        |       |        |     |      | x   |
| flightphp   |       |        | x   |      |     |
| ghostwriter | x     |        |     |      |     |
| illuminate  |       |        |     | x    |     |
| joomla      | x     |        |     |      |     |
| laminas     |       |        |     |      |     |
| league      |       |        | x   |      | x   |
| nette       |       | x      |     |      |     |
| phalcon     |       |        | x   |      |     |
| phpdi       |       |        |     | x    |     |
| pimple      |       |        | x   |      |     |
| ray         |       |        | x   |      |     |
| rdlowrey    |       |        |     | x    |     |
| symfony     |       |        |     |      |     |
| tempest     |       |        | x   |      |     |
| yii         |       |        | x   |      |     |


## Set a shared service instance

The projects allow setting an already-instantiated service into the container,
perhaps overwritng an existing service.

|             | Signature                                                                                     |
| ----------- | --------------------------------------------------------------------------------------------- |
| aura        | `set(string $service, object $val) : $this`                                                   |
| flightphp   | -                                                                                             |
| ghostwriter | `set(string $id, object $value) : void`                                                       |
| illuminate  | `instance(string $abstract, object $concrete) : void`                                         |
| joomla      | `set($key, $value, true) : $this`                                                             |
| laminas     | `setInstance(string $name, $service): $this`                                                  |
| league      | `add(string $id, object $concrete) : DefinitionInterface`                                     |
| nette       | `addService(string $name, object $service) : $this`                                           |
| phalcon     | `setShared(string $name, object $service) : ServiceInterface`                                 |
| phpdi       | `set(string $name, mixed $value) : void` (1)                                                  |
| pimple      | `offsetSet(string $id, mixed $value) : void`                                                  |
| ray         | -                                                                                             |
| rdlowrey    | `share(object $instance) : $this` (2)                                                         |
| symfony     | `set(string $id, ?object $service) : void`                                                    |
| tempest     | `singleton(string $className, object $definition, null\|string\|UnitEnum $tag = null) : self` |
| yii         | -                                                                                             |

1. `phpdi` overloads this method for definitions, factories, and for setting instances.

2. `rdlowrey` sets the service name to `get_class($instance)`

> N.b.: Already-instantiated services can only be shared; the containers do not
> return new instances of them. Except Joomla which clones it.

## Set a service factory callable

The projects allow the consumer to set a callable as a factory for creating new service instances.

|             | Notes | Signature                                                                                     |
| ----------- | ----- | --------------------------------------------------------------------------------------------- |
| aura        |       | `set($key, Closure $val) : $this`                                                             |
| flightphp   |       | `set($id, callable $concrete) : $this`                                                        |
| ghostwriter | (1)   | `factory(string $id, string $factory) : void` |
| illuminate  |       | `bind(string $abstract, callable $concrete) : void |
| joomla      |       | `set(string $key, callable $value) : $this` |
| laminas     |       | - |
| league      |       | ? |
| nette       |       | `addService(string $name, Closure $service) : $this` |
| phalcon     | (2)   | `set(string $name, Closure $definition) : mixed` |
| phpdi       | (3)   | `factory(callable $factory) : FactoryDefinitionHelper` |
| pimple      |       | `offsetSet(string $id, Closure $value)`                         |
| ray         |       | -                                                               |
| rdlowrey    |       | `delegate(string $name, callable $callableOrMethodStr) : $this` |
| symfony     |       | - |
| tempest     |       | `register(string $className, callable $definition) : $this` |
| yii         |       | - |

1. `ghostwriter` treats the `$factory` value as the string class name of an invokable object.

2. `phalcon` binds `$this` (the container) to the callable at invocation time.

3. `phpdi` uses a the `factory()` function to create a FactoryDefinitionHelper.

## Factory Callable Signature

When a callable is used as a factory for creating a new instance, this is the callable signature expected by the container.

|             | `(Container) : return` | `() : return` | Other                       |
| ----------- | ---------------------- | ------------- | --------------------------- |
| aura        | `(Resolver) : object`  |               |                             |
| flightphp   | `(Container) : object` |               |                             |
| ghostwriter | `(Container) : object` |               |                             |
| illuminate  | `(Container) : object` |               |                             |
| joomla      | `(Container) : object` |               |                             |
| laminas     |                        |               | -                           |
| league      |                        | `() : object` |                             |
| nette       |                        | `() : object` |                             |
| phalcon     |                        | `() : object` |                             |
| phpdi       |                        |               | `(mixed ...$args) : object` |
| pimple      | `(Pimple $c) : mixed`  |               |                             |
| ray         |                        |               | -                           |
| rdlowrey    |                        |               | `(array) : object`          |
| symfony     | `(Container) : object` |               |                             |
| tempest     | `(Container) : object` |               |                             |
| yii         |                        |               | -                           |

None of the factory callables expect to receive override arguments from a custom factory.

## Re-/un-settable

Does the project allow services already instantiated in the container to be unset, reset, replaced, etc?

|             | Yes | No  |
| ----------- | --- | --- |
| aura        | (1) | (1) |
| flightphp   | x   |     |
| ghostwriter | x   |     |
| illuminate  | x   |     |
| joomla      | (2) | (2) |
| laminas     | x   |     |
| league      | x   |     |
| nette       | x   |     |
| phalcon     | x   |     |
| phpdi       | x   |     |
| pimple      | x   |     |
| ray         |     | x   |
| rdlowrey    |     | x   |
| symfony     | x   |     |
| tempest     | x   |     |
| yii         |     | x   |

1. `aura` is settable until the container is `lock()`ed, and is not settable afterwards.

2. `joomla` allows setting a service as "protected" which protects it from being overwritten; the default is "not protected".

## Instantiate a service

Contains the instantiation logic, including autowiring.

Usually internal to container itself (though maybe not public); sometimes on a
separate object that receives the container; sometimes the service name is
needed, other times not.

|             | Internal? | Public? | Named? | Signature |
| ----------- | --------- | ------- | ------ | --------- |
| aura        |           | x       |        | `resolve(Blueprint $blueprint, array $contextualBlueprints = []) : object` |
| flightphp   | x         |         | x      | `resolve(string $id) : object` |
| ghostwriter | x         |         | x      | `instantiate(string $service, array $arguments = []): object` |
| illuminate  | x         |         | x      | `resolve($abstract, $parameters = [], $raiseEvents = true) : ($abstract is class-string<TClass> ? TClass : mixed)` |
| joomla      | x         | x       | x      | `buildObject($resourceName, $shared = false) : object\|false` |
| laminas     |           | x       | x      | `create(string $name, array $params = []) : object` |
| league      | x         |         | x      | `resolve(string $id, bool $new = false): mixed` |
| nette       | x         | x       | x      | `createInstance(string $class, array $args = []): object` |
| phalcon     |           | x       |        | `resolve(?array $parameters = null, ?DiInterface $container = null) : object` |
| phpdi       |           | x       |        | `resolve(Definition $definition, array $parameters = []) : mixed` |
| pimple      | x         | x       | x      | `offsetGet($id) : mixed` (1) |
| ray         |           | x       |        | `inject(Container $container) : object` |
| rdlowrey    | x         | x       | x      | `make($name, array $args = array()) : object` |
| symfony     | x         |         | x      | `make(self $container, string $id, int $invalidBehavior): ?object` |
| tempest     | x         |         | x      | `resolve(string $className, null\|string\|UnitEnum $tag = null, mixed ...$params): object` |
| yii         | x         |         | x      | `build(string $id): mixed` |

1. `pimple` may return a shared instance.

Public and named:

- internal: joomla, nette (args), pimple, rdlowrey (args).
- external: laminas (args)

Terminology:

|             | Build | Create | Get | Inject | Instantiate | Make | Resolve |
| ----------- | ----- | ------ | --- | ------ | ----------- | ---- | ------- |
| aura        |       |        |     |        |             |      | x       |
| flightphp   |       |        |     |        |             |      | x       |
| ghostwriter |       |        |     |        | x           |      |         |
| illuminate  |       |        |     |        |             |      | x       |
| joomla      | x     |        |     |        |             |      |         |
| laminas     |       | x      |     |        |             |      |         |
| league      |       |        |     |        |             |      | x       |
| nette       |       | x      |     |        |             |      |         |
| phalcon     |       |        |     |        |             |      | x       |
| phpdi       |       |        |     |        |             |      | x       |
| pimple      |       |        | x   |        |             |      |         |
| ray         |       |        |     | x      |             |      |         |
| rdlowrey    |       |        |     |        |             | x    |         |
| symfony     |       |        |     |        |             | x    |         |
| tempest     |       |        |     |        |             |      | x       |
| yii         | x     |        |     |        |             |      |         |

## Providing, registering, defining, configuring, or loading services

Programmatically (imperatively?) sets one or more services into a container; generally a separate class.

|             | Class Name               | Method                                  |
| ----------- | ------------------------ | --------------------------------------- |
| aura        | ContainerConfig          | `define(Container $di) : void`          |
| flightphp   |                          |                                         |
| ghostwriter |                          |                                         |
| illuminate  | ServiceProvider          | `register() : void` (1)                 |
| joomla      | ServiceProviderInterface | `register(Container $container) : void` |
| laminas     | (2)                      |                                         |
| league      | ServiceProviderInterface | `register(): void` (3)                  |
| nette       | (4)                      |                                         |
| phalcon     | ServiceProviderInterface | `register(DiInterface di) : void`       |
| phpdi       | (5)                      |                                         |
| pimple      | ServiceProviderInterface | `register(Container $pimple)`           |
| ray         | (6)                      |                                         |
| rdlowrey    |                          |                                         |
| symfony     | (7)                      |                                         |
| tempest     |                          |                                         |
| yii         | (8)                      |                                         |

(Config file is more declarative.)

1. `illuminate` gives access to the container via `$this->app`.

2. `laminas` does service configuration, not provision per se.

3. `league` gives access to the container via `$this->getContainer()`.

4. `nette` does service configuration, not provision per se.

5. `phpdi` does service configuration through definitions separate from the container, not provision per se, though it is programmatic.

6. `ray` collects definitions from source code attributes/annotations.

7. `symfony` does service configuration, not provision per se.

8. `yii` does service configuration through definitions separate from the container, not provision per se.

## Creating the container itself

Very few of the researched projects offer a factory or builder for the container itself.

|             | Class | Method |
| ----------- | ----- | ------ |
| aura        | _ContainerBuilder_ | `newConfiguredInstance(array $configClasses = [], bool $autoResolve = false) : Container` |
| flightphp   | - | - |
| ghostwriter | - | - |
| illuminate  | - | - |
| joomla      | - | - |
| laminas     | - | - |
| league      | - | - |
| nette       | (1) | - |
| phalcon     | - | - |
| phpdi       | _ContainerBuilder_ | `build() : Container` |
| pimple      | - | - |
| ray         | _ContainerFactory_ | `__invoke($module, string $classDir) : Container` |
| rdlowrey    | - | - |
| symfony     | (2) | - |
| tempest     | - | - |
| yii         | - | - |

1. `nette` _ContainerBuilder_ looks like it is part of a compiler system.

2. `symfony` _ContainerBuilder_ is itself a container.

## Service aliases

When an abstract or interface service name is requested, alias it to a concrete
service instead.

|             | Method, Property, or Notation |
| ----------- | ----------------------------- |
| aura        | `$di->types[Abstract::class] = $di->lazyGet(Concrete::class);` |
| flightphp   | `set(Abstract::class, Concrete::class) : void` |
| ghostwriter | `alias(Abstract::class, Concrete::class): void` |
| illuminate  | `alias(Abstract::class, Concrete::class) : void` |
| joomla      | `alias(Abstract::class, Concrete::class) : $this` |
| laminas     | (1) |
| league      | `add(Abstract::class, Concrete::class) : void` |
| nette       | `addAlias(Abstract::class, Concrete::class): void` |
| phalcon     | - |
| phpdi       | `[Abstract::class => DI\get(Concrete::class)]` (2) |
| pimple      | `$pimple[Abstract::class] = fn($c) => return $c[Concrete::class];` |
| ray         | - |
| rdlowrey    | `alias(Abstract::class, Concrete::class) : $this` |
| symfony     | `alias(Abstract::class, Concrete::class) : AliasConfigurator`|
| tempest     | - |
| yii         | - | # maybe it does, check set() again

1. `laminas` aliases abstract to concrete types via configuration, not a method.

2. `php-di` aliases an abstract service `$name` to a service instance.

## Service Tagging

Tag one or more services, then get the collection of services with that tag.

### Setting Tags On Services

|             | Set |
| ----------- | ----------------------------- |
| aura        | - |
| flightphp   | - |
| ghostwriter | - |
| illuminate  | `tag(array\|string $abstracts, mixed ...$tags) : void` |
| joomla      | `tag($tag, array $keys) : $this` |
| laminas     | - |
| league      | (1) |
| nette       | (2) |
| phalcon     | - |
| phpdi       | - |
| pimple      | - |
| ray         | - |
| rdlowrey    | - |
| symfony     | (3) |
| tempest     | `singleton(string $className, mixed $definition, null\|string\|UnitEnum $tag = null): self;` |
| yii         | (4) |

1. `league` offers `addTag(string $tag)` on each _Definition_

2. `nette` offers `addTag(string $tag, mixed $attr = true)` on each _Definition_

3. `symfony` offers `setTags(array $tags)` on each _Definition_

4. `yii` offers `withTags(array<tag-id,service-name-array> $tags)` on _ContainerConfig_

Of the 7 that offer some form of service tagging, 3 do so via a _Definition_ and not the container or its config.

### Getting Tagged Services

|             | Get |
| ----------- | ----------------------------- |
| aura        | - |
| flightphp   | - |
| ghostwriter | - |
| illuminate  | `function tagged(string $tag) : iterable<object>` |
| joomla      | `getTagged(string $tag) : object[]` |
| laminas     | - |
| league      | (1) |
| nette       | `findByTag(string $tag): array<service-name-string, attributes-array>` |
| phalcon     | - |
| phpdi       | - |
| pimple      | - |
| ray         | - |
| rdlowrey    | - |
| symfony     | `findTaggedServiceIds(string $name): array<int, service-id>` |
| tempest     | `get(string $className, null\|string\|UnitEnum $tag = null, mixed ...$params) : object` (2) |
| yii         | `get(TagReference::id($tag)) : object[]` |

1. `league` offers `resolveTagged($tag)` and `resolveTaggedNew($tag)` on a _DefinitionAggregate_

2. `tempest` appears to return only one tagged service at a time; looks like a way to label different instances of the same services.

* * *

## Topics not analyzed

- Container ...

    - Attribute/annotation collection and resolution

    - Compiling

    - Compositing (aka "delegation" to a hierarchy of other containers)

    - Invokables (inject arguments on function/method/callable, then call & return)

    - Serializing

- Definitions (these should be here and not on the service collection)

    - Property injection

    - Setter injection

    - Extension

    - Decoration/replacement

    - Contextual binding (when class Foo wants Bar give Baz otherwise give Dib)
