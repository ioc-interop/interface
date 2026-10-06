# Ioc-Interop Standard Interface Package

[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat-square)](https://github.com/php-pds/skeleton)
[![PDS Composer Script Names](https://img.shields.io/badge/pds-composer--script--names-blue?style=flat-square)](https://github.com/php-pds/composer-script-names)

Ioc-Interop provides an interoperable package of standard interfaces for
inversion-of-control (IOC) service container functionality. It reflects,
refines, and reconciles the common practices identified within
[several pre-existing projects][README-RESEARCH.md].

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD",
"SHOULD NOT", "RECOMMENDED",  "MAY", and "OPTIONAL" in this document are to be
interpreted as described in [BCP 14][] ([RFC 2119][], [RFC 8174][]).

This package attempts to adhere to the [Package Development Standards](https://php-pds.com/) approach to [naming and versioning](https://php-pds.com/#naming-and-versioning).

## Interfaces

This package defines the following interfaces:

{{= list }}

{{= docs }}

## Implementations

Implementations MAY define additional class members not defined in these interfaces.

Notes:

- **Reference implementations** are available at <https://github.com/ioc-interop/impl>.

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
services. The affordances for *managing* and *producing* services are
separate concerns.

Earlier drafts of Ioc-Interop were much more expansive, including a resolver
subsystem and a service management subsystem. These have been extracted to
separate standards, each of which is dependent on Ioc-Interop:

- [Service-Interop][]
- [Resolver-Interop][]

This separation helps to maintain a boundary between the needs of service
consumers (afforded by Ioc-Interop) and service producers (afforded by
[Service-Interop][] and [Resolver-Interop][]).

Note that Ioc-Interop is independent of [Service-Interop][] and
[Resolver-Interop][]. Ioc-Interop implementations can use them, or avoid them,
as implementors see fit.

### Is [_IocContainer_][] for Dependency Injection or is it a Service Locator?

[_IocContainer_][] acts as a Service Locator only when it is used as a dependency
in order to retrieve other dependencies from it.

### Why does [_IocContainer_][] disallow non-object values?

[_IocContainer_][] is explicitly a *service* container, not a general config
container for [scalar][] or [array][] values.

Limiting services to objects helps maintain consistent expectations regarding
service types and behavior. Of the researched projects, 11 return `object`, and
8 return `mixed`, so this restriction is consistent with the majority.

Ioc-Interop recognizes that implementors and consumers often want to make config
values easily available, though Ioc-Interop questions what it means (or if it
is possible) to get a "shared" scalar or array that works the same as a "shared"
object.

With that in mind, Ioc-Interop encourages the use of one or more config services
or value objects to make those values available, instead of storing config
values directly inside a container.

### Why does a `class-string` name not guarantee an instance of that class?

When the `$serviceName` is a `class-string`, the `ioc_service_object` return
type resolves to that class. It would seem to follow that `getService()`
should be required to return an instance of it.

It cannot be required, because whether a name *is* a `class-string` is not a
property of the name. Static analysis answers that question by looking for a
class of that name in the codebase being analyzed, case-insensitively. A
service labeled `logger` is a `class-string` in a project that happens to
define a `Logger` class, and a plain string in one that does not; the same
call means different things in different codebases. A directive whose
applicability varies that way cannot be implemented, because the container
has no way to know which case it is in.

Of the researched projects, Laravel's container and yiisoft/di narrow the
return type on a `class-string` name in exactly this way, and neither
requires the returned object to be an instance of it. Aura.Di and Nette DI
decline to narrow at all, returning `object` from their name-keyed methods.

### Why does [_IocContainer_][] define `getService()` and not just `get()`?

The vast majority of researched projects, whether PSR-11 conforming or not, use
the method name `get()`. Contra the research, Ioc-Interop asserts that `get()`
is too generic, and that the method name should hint at what is being gotten;
thus, `getService()`.

### Why does Ioc-Interop offer an [_IocContainerFactory_][]?

Container-creation logic is a minority position among the researched projects:
only four offer any way to create the container itself, and each does so with a
different signature.

Contra the research, Ioc-Interop asserts that container *creation* is a
separate concern from container *use*, and thus deserves an interface of its
own. Separating them affords creating a container more than once: per request
or per job in a long-running runtime, per test case, or per tenant.

Implementing [_IocContainerFactory_][] is optional. Service consumers should
typehint on [_IocContainer_][].

### Why must every failure throw an [_IocThrowable_][]?

None of the researched projects do so. Every one of them lets an exception
from a consumer-supplied factory or constructor propagate unchanged, and
throws a container exception only for failures it detects itself, such as an
unknown name or an unresolvable dependency.

Contra the research, Ioc-Interop asserts that a consumer calling
`getService()` should have exactly one thing to catch. A container that
propagates arbitrary throwables offers no contract at the call site: the
consumer cannot know what might emerge, and so cannot write against any
container other than the one in front of them. [PSR-11][] takes the same
position, documenting its container exception for any error while retrieving
an entry, though none of the researched projects honor it.

Nothing need be discarded: an implementation can retain the originating
throwable as the `$previous` [_Throwable_][], so a consumer that needs the
underlying cause can reach it. Ioc-Interop recommends this but does not
require it, since an implementation that falls back from a failed means of
producing the service to another isn't obligated to account for the
attempt it recovered from.

The cost is real: a consumer can no longer catch a specific exception type
around a call to `getService()`, and must catch [_IocThrowable_][] and, when
one is present, examine the `$previous` [_Throwable_][] instead.

* * *

[_Error_]: https://php.net/Error
[_Exception_]: https://php.net/Exception
[_IocContainer_]: #ioccontainer
[_IocContainerFactory_]: #ioccontainerfactory
[_IocThrowable_]: #iocthrowable
[_IocTypeAliases_]: #ioctypealiases
[_Throwable_]: https://php.net/Throwable
[BCP 14]: https://datatracker.ietf.org/doc/bcp14/
[PSR-11]: https://www.php-fig.org/psr/psr-11/
[README-RESEARCH.md]: ./README-RESEARCH.md
[Resolver-Interop]: https://github.com/resolver-interop/interface
[RFC 2119]: https://datatracker.ietf.org/doc/html/rfc2119
[RFC 8174]: https://datatracker.ietf.org/doc/html/rfc8174
[Service-Interop]: https://github.com/service-interop/interface
[scalar]: https://www.php.net/manual/en/language.types.type-system.php#language.types.type-system.atomic.scalar
[array]: https://www.php.net/manual/en/language.types.array.php
