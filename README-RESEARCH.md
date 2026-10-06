# Research

Ioc-Interop is based on research into the following projects that provide
inversion-of-control containers:

- [aura/di](https://github.com/auraphp/Aura.Di) (aura)
- [level-2/dice](https://github.com/Level-2/Dice) (dice)
- [flightphp/container](https://github.com/flightphp/container) (flightphp)
- [ghostwriter/container](https://github.com/ghostwriter/container) (ghostwriter)
- [illuminate/container](https://github.com/illuminate/container) (illuminate)
- [joomla/di](https://github.com/joomla-framework/di) (joomla)
- [laminas/laminas-di](https://github.com/laminas/laminas-di) (laminas)
- [league/container](https://github.com/thephpleague/container) (league)
- [mindplay/unbox](https://github.com/mindplay-dk/unbox) (mindplay)
- [nette/di](https://github.com/nette/di) (nette)
- [pimple/pimple](https://github.com/silexphp/Pimple) (pimple)
- [Phalcon 4.x](https://github.com/phalcon/cphalcon/) (phalcon)
- [php-di/php-di](https://github.com/PHP-DI/PHP-DI) (phpdi)
- [ray/di](https://github.com/ray-di/Ray.Di) (ray)
- [rdlowrey/auryn](https://github.com/rdlowrey/auryn) (rdlowrey)
- [symfony/dependency-injection](https://github.com/symfony/dependency-injection) (symfony)
- [tempest/container](https://github.com/tempestphp/tempest-container) (tempest)
- [yiisoft/di](https://github.com/yiisoft/di) (yii-di)
- [yiisoft/factory](https://github.com/yiisoft/di) (yii-factory)

> **Note:**
>
> The `yii` projects are unusual, in that they keep shared service functionality
> in a `di` package, but keep new-instance functionality in a separate `factory`
> package. Yii also ships `yiisoft/injector` (callable-argument injection) and
> `yiisoft/definitions` (service-definition objects); neither is a container by
> itself, so they are out of scope here.

> **Note:**
>
> `dice` is unique among the surveyed containers in being immutable: each
> call to `Dice::addRule()` or `Dice::addRules()` clones the container and
> returns the modified copy, leaving the original unchanged. Configuration
> must be reassigned (`$dice = $dice->addRule(...)`).

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


## PSR-11 Signatures

- "Yes": is a conforming `get(string $id) : mixed` signature
- "Opt": offers a conforming `get(string $id) : mixed` signature as an option
- "Ish": is a modified `get(string $id) : object` (not `mixed`) signature
- "No": non-conforming

|             | Yes | Opt | Ish | No |
| ----------- | --- | --- | --- | -- |
| aura        | x   |     |     |    |
| dice        |     |     |     | x  |
| ghostwriter |     |     |     | x  |
| flightphp   |     |     | x   |    |
| joomla      | x   |     |     |    |
| illuminate  | x   |     |     |    |
| laminas     |     |     | x   |    |
| league      | x   |     |     |    |
| mindplay    | x   |     |     |    |
| nette       |     |     |     | x  |
| phalcon     |     | x   |     |    |
| phpdi       | x   |     |     |    |
| pimple      |     | x   |     |    |
| ray         |     |     |     | x  |
| rdlowrey    |     |     |     | x  |
| symfony     | x   |     |     |    |
| tempest     |     |     |     | x  |
| yii-di      | x   |     |     |    |
| yii-factory |     |     |     | x  |

10 projects offer a conforming PSR-11 signature; 9 offer modified or
non-conforming PSR-11 method signature.

> **Note:**
>
> The "Opt" projects offer PSR-11 conformance via wrapper classes:
>
> - `phalcon`: `Phalcon\Container` delegates `get()` to `Phalcon\Di::getShared()`.
> - `pimple`: `Pimple\Psr11\Container` delegates `get()` to `Pimple\Container::offsetGet()`.

## Service Types

PSR-11 signature conformance notwithstanding, reading the container code itself
indicates these projects return these types from the container:

|             | `object` | `mixed` |
| ----------- | -------- | ------- |
| aura        | x        |         |
| dice        | x        |         |
| flightphp   | x        |         |
| ghostwriter | x        |         |
| illuminate  |          | x       |
| joomla      |          | x       |
| laminas     | x        |         |
| league      |          | x       |
| mindplay    |          | x       |
| nette       | x        |         |
| phalcon     | x        |         |
| phpdi       |          | x       |
| pimple      |          | x       |
| ray         | x        |         |
| rdlowrey    | x        |         |
| symfony     | x (1)    |         |
| tempest     | x        |         |
| yii-di      |          | x       |
| yii-factory |          | x       |

1. `symfony` is `?object`.

11 of the projects return objects; 8 return anything at all.

## Service Names

The projects were exercised by registering and retrieving a service under four
different names: a defined class name (`stdClass`), a name conforming to the
rules for naming a class but not defined as one (`foo_1`), a label that is not
a valid class name (`db.replica`), and a numeric string (`123`).

|             | Accepts as a service name                    |
| ----------- | -------------------------------------------- |
| aura        | any string                                   |
| dice        | any string                                   |
| flightphp   | any string                                   |
| ghostwriter | only a defined class, interface, or enum (1) |
| illuminate  | any string                                   |
| joomla      | any string                                   |
| laminas     | - (2)                                        |
| league      | any string                                   |
| mindplay    | any string                                   |
| nette       | any string                                   |
| phalcon     | - (2)                                        |
| phpdi       | any string                                   |
| pimple      | any string                                   |
| ray         | - (2)                                        |
| rdlowrey    | any string (3)                               |
| symfony     | any string                                   |
| tempest     | only a defined class (4)                     |
| yii-di      | any string except numeric (5)                |
| yii-factory | - (2)                                        |

1. `ghostwriter` asserts the name is valid before storing it, throwing
   _ServiceNotFoundException_ with the message `Service "foo_1" is not a valid
   class, interface, or enum.`

2. Not exercised.

3. `rdlowrey` sets the service name to `get_class($instance)` when registering
   an already-instantiated service.

4. `tempest` names the parameter `$className` and reflects on it.

5. `yii-di` throws _InvalidConfigException_ for `123`. PHP coerces numeric
   string array keys to integers, so the name does not survive as a string.

Of the 15 projects exercised, 13 accept a label that is not a class name; 2
require the name to be a defined class. None require the name merely to conform
to the rules for naming a class.

## Has a service

The projects afford checking to see if the container "has" a service, but the
meaning is slightly different between them all.

|             | Signature                                                           | Meaning                                                   |
| ----------- | ------------------------------------------------------------------- | --------------------------------------------------------- |
| aura        | `has(string $id) : bool`                                            | "Does a service definition exist?"                        |
| dice        | -                                                                   | -                                                         |
| flightphp   | `has(string $id) : bool`                                            | "Is an entry key set for $id?"                            |
| ghostwriter | `has(string $id) : bool`                                            | "Does get() return a service?"                            |
| illuminate  | `has(string $id) : bool`                                            | "Has $id been bound?"                                     |
| joomla      | `has(string $resourceName) : bool`                                  | "Is a resource key set here or in parent container?"      |
| laminas     | `has(string $name) : bool`                                          | "Has an instance, or can injector create one?"            |
| league      | `has(string $id) : bool`                                            | "Has a definition, has a tag, has provided, has delegate" |
| mindplay    | `has(string $name) : bool`                                          | "Has an instance or factory"
| nette       | `hasService(string $name) : bool`                                   | "Has an instance or factory"                              |
| phalcon     | `has(string $name) : bool`                                          | "Has a services key"                                      |
| phpdi       | `has(string $id) : bool`                                            | "Has an instance, or a resolvable definition"             |
| pimple      | `offsetExists(string $id) : bool`                                   | "Is a key set for the $id?"                               |
| ray         | -                                                                   | -                                                         |
| rdlowrey    | -                                                                   | -                                                         |
| symfony     | `has(string $id) : bool`                                            | "Has an instance, or is mapped from a file or method"     |
| tempest     | `has(string $className, null\|string\|UnitEnum $tag = null) : bool` | "Has a definition or a singleton"                         |
| yii-di      | `has(string $id) : bool`                                            | "Has a definition or a tag"                               |
| yii-factory | -                                                                   | -                                                         |

The projects were further exercised to see whether "has" and "get" agree with
each other. Two cases: asking for a class that is defined but was never
registered, and asking for a registered service whose factory throws.

|             | Unregistered class    | Factory throws      |
| ----------- | --------------------- | ------------------- |
| aura        | `false`, then throws  | -                   |
| dice        | - (1)                 | - (1)               |
| flightphp   | `false`, then returns | `true`, then throws |
| ghostwriter | `true`, then returns  | -                   |
| illuminate  | `false`, then returns | `true`, then throws |
| joomla      | `false`, then throws  | `true`, then throws |
| laminas     | - (2)                 | - (2)               |
| league      | `false`, then throws  | `true`, then throws |
| mindplay    | `false`, then throws  | `true`, then throws |
| nette       | `false`, then throws  | -                   |
| phalcon     | - (2)                 | - (2)               |
| phpdi       | `true`, then returns  | -                   |
| pimple      | `false`, then throws  | `true`, then throws |
| ray         | - (1)                 | - (1)               |
| rdlowrey    | - (1)                 | - (1)               |
| symfony     | `false`, then throws  | `true`, then throws |
| tempest     | `false`, then returns | `true`, then throws |
| yii-di      | `true`, then returns  | -                   |
| yii-factory | - (1)                 | - (1)               |

1. Affords no "has" method; see the table above.

2. Not exercised.

Of the 13 projects exercised on the first case, 3 report `false` and then return
the service anyway (`flightphp`, `illuminate`, `tempest`), and 3 report `true`
for a service that was never registered with them (`ghostwriter`, `phpdi`,
`yii-di`). Of the 8 exercised on the second case, all 8 report `true` and then
throw.

Neither answer binds the other: `true` does not promise that retrieval will
succeed, and `false` does not promise that it will fail.


## Get a service

The projects use these method signatures to get a service from the container.
Some of them have a separate method to get a "new" service instance, others
define a lifetime ("singleton", "shared", "scoped") on the service itself. In
those cases, "get an instance" might return a new instance or it might return a
shared instance, and you won't know from the call-site.

|             | Service-Defined Lifetime |  Signature |
| ----------- | ------------------------ | ---------- |
| aura        |                          | `get(string $id) : object` |
| dice        | x (1)                    | `create(string $name, array $args = [], array $share = []) : object` |
| flightphp   | x (2)                    | `get(string $id) : object` |
| ghostwriter |                          | `get(string $id) : object` |
| illuminate  | x (3)                    | `get(string $id) : ($id is class-string<TClass> ? TClass : mixed)` |
| joomla      | x (4)                    | `get($resourceName) : mixed` |
| laminas     |                          | `get(string $name) : object` |
| league      | x (5)                    | `get(string $id) : mixed` |
| mindplay    |                          | `get(string $name) : ($name is class-string<T> ? T : mixed)` |
| nette       |                          | `getService(string $name) : object` |
| phalcon     | x (6)                    | `get(string $name, $parameters = null) : mixed` |
| phpdi       |                          | `get(string $id) : mixed` |
| pimple      | x (7)                    | `offsetGet(string $id) : mixed` |
| ray         |                          | `getInstance($interface, $name = Name::ANY)` |
| rdlowrey    | x (8)                    | `make($name, array $args = array()) : mixed` |
| symfony     |                          | `get(string $id, int $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE) : ?object` |
| tempest     | x (9)                    | `get(string $className, null\|string\|UnitEnum $tag = null, mixed ...$params) : object` |
| yii-di      |                          | `get(string $id) : ($id is class-string ? T : mixed)` |
| yii-factory |                          | - |

1. `dice` will return a new instance unless the service's rule sets
    `'shared' => true`. The `$share` parameter is unique among the surveyed
    containers: it specifies instances to share only within the current
    construction subtree, not at the container level.

2. `flightphp` will return a new instance unless the service was set as a `singleton()`.

3. `illuminate` will return a new instance unless the service was ...
    - registered as a shared service via `singleton($abstract, $concrete = null)`
    - set directly as a shared service via `instance($abstract, $instance)`
    - bound with `$shared = true`: `bind($abstract, $concrete = null, $shared = false)`

4. `joomla` will return a new instance unless the service was defined as shared.

5. `league` will return a new instance unless the service was set as shared.

6. `phalcon` will return a new instance unless the service was set as shared.
    `phalcon\Di` also offers `getShared(string $name, $parameters = null) : mixed`,
    which always returns the shared instance regardless of the service's lifetime
    configuration. `phalcon\Di` further implements `ArrayAccess`, with
    `offsetGet` aliasing `getShared` and `offsetExists` aliasing `has`.

7. `pimple` will return a new instance if the service was set as a `factory()`

8. `rdlowrey` will return a new instance unless the service was set as shared, in which case the `$args` are ignored.

9. `tempest` will return a new instance unless the service was set as shared.

`phpdi` also offers `make(string $name, array $parameters = []) : mixed` to
bypass caching and return a new instance.

## Creating the container itself

Very few of the researched projects offer a factory or builder for the container itself.

|             | Class | Method |
| ----------- | ----- | ------ |
| aura        | _ContainerBuilder_ | `newConfiguredInstance(array $configClasses = [], bool $autoResolve = false) : Container` |
| dice        | - | - |
| flightphp   | - | - |
| ghostwriter | - | - |
| illuminate  | - | - |
| joomla      | - | - |
| laminas     | - | - |
| league      | - | - |
| mindplay    | _ContainerFactory_ | `createContainer() : Container` |
| nette       | (1) | - |
| phalcon     | - | - |
| phpdi       | _ContainerBuilder_ | `build() : Container` |
| pimple      | - | - |
| ray         | _ContainerFactory_ | `__invoke($module, string $classDir) : Container` |
| rdlowrey    | - | - |
| symfony     | (2) | - |
| tempest     | - | - |
| yii-di      | - | - |

1. `nette` _ContainerBuilder_ looks like it is part of a compiler system.

2. `symfony` _ContainerBuilder_ is itself a container.

