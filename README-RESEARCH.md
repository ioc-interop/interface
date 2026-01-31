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
- [mindplay/unbox](https://github.com/mindplay-dk/unbox) (mindplay)
- [nette/di](https://github.com/nette/di) (netter)
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
> package.

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
| rdlowrey    | x   |     |     |    |
| symfony     | x   |     |     |    |
| tempest     |     |     |     | x  |
| yii-di      | x   |     |     |    |
| yii-factory |     |     |     | x  |

11 projects offer a conforming PSR-11 signatures; 7 offer modified or
non-conforming PSR-11 method signature.

## Service Types

PSR-11 signature conformance notwithstanding, reading the container code itself
indicates these projects return these types from the container:

|             | `object` | `mixed` |
| ----------- | -------- | ------- |
| aura        | x        |         |
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

10 of the projects return objects; 8 return anything at all.

## Has a service

The projects afford checking to see if the container "has" a service, but the
meaning is slightly different between them all.

|             | Signature                                                           | Meaning                                                   |
| ----------- | ------------------------------------------------------------------- | --------------------------------------------------------- |
| aura        | `has(string $id) : bool`                                            | "Does a service definition exist?"                        |
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


## Get a service

The projects use these method signatures to get a service from the container.
Some of them have a separate method to get a "new" service instance, others
define a lifetime ("singleton", "shared", "scoped") on the service itself. In
those cases, "get an instance" might return a new instance or it might return a
shared instance, and you won't know from the call-site.

|             | Service-Defined Lifetime |  Signature |
| ----------- | ------------------------ | ---------- |
| aura        |                          | `get(string $id) : object` |
| flightphp   | x (1)                    | `get(string $id) : object` |
| ghostwriter |                          | `get(string $id) : object` |
| illuminate  | x (2)                    | `get(string $id) : ($id is class-string<TClass> ? TClass : mixed)` |
| joomla      | x (3)                    | `get($resourceName) : mixed` |
| laminas     |                          | `get(string $name) : object` |
| league      | x (4)                    | `get(string $id) : mixed` |
| mindplay    |                          | `get(string $name) : ($name is class-string<T> ? T : mixed)` |
| nette       |                          | `getService(string $name) : object` |
| phalcon     | x (5)                    | `getShared(string $name, $parameters = null) : object` |
| phpdi       |                          | `get(string $id) : mixed` |
| pimple      | x (6)                    | `offsetGet(string $id) : mixed` |
| ray         |                          | `getInstance($interface, $name = Name::ANY)` |
| rdlowrey    | x (7)                    | `make($name, array $args = array()) : mixed` |
| symfony     |                          | `get(string $id, int $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE) : ?object` |
| tempest     | x (8)                    | `get(string $className, null\|string\|UnitEnum $tag = null, mixed ...$params) : object` |
| yii-di      |                          | `get(string $id) : ($id is class-string ? T : mixed)` |
| yii-factory |                          | - |

1. `flightphp` will return a new instance unless the service was set as a `singleton()`.

2. `illuminate` will return a new instance unless the service was ...
    - registered as a shared service via `singleton($abstract, $concrete = null)`
    - set directly as a shared service via `instance($abstract, $instance)`
    - bound with `$shared = true`: `bind($abstract, $concrete = null, $shared = false)`

3. `joomla` will return a new instance unless the service was defined as shared.

4. `league` will return a new instance unless the service was set as shared.

5. `phalcon` will return a new instance unless the service was set as shared.

6. `pimple` will return a new instance if the service was set as a `factory()`

7. `rdlowrey` will return a new instance unless the service was set as shared, in which case the `$args` are ignored.

8. `tempest` will return a new instance unless the service was set as shared.

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

