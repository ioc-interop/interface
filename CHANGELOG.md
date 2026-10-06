# Change Log

## 1.0.0-beta1

Refinements from continued review and research:

- BC break: service names must be non-empty. `ioc_service_name_string` is
  now `non-empty-string`, replacing `class-string<T>|string`.

- _IocContainer_ `hasService()` asks whether the container *might* return an
  object for the service name, not whether it is able to return an instance
  *of* it. Implementations MUST return `false` only when the container
  knows in advance that `getService()` will not be able to return an
  object, and MUST NOT produce a service, by instantiation or any other
  means, in order to answer. A `false` result is therefore conclusive;
  a `true` result is not, and either answer speaks only to the call that
  produced it.

- _IocContainer_ `getService()` returns an object *for* the service name,
  rather than an instance *of* it, and MUST throw _IocThrowable_ for any
  failure, not only for an unrecognized service name, regardless of the
  underlying cause.

- _IocContainer_ `getService()` recommends, but does not require, making
  the thrown _IocThrowable_ as informative as possible: retaining a
  causing _Error_ or _Exception_ as the `$previous` _Throwable_, or
  describing the resolution path in the message, where applicable.

- _IocContainer_ `getService()` warns that any name matching an existing
  class is treated as a class-string by static analysis, whatever the name
  was meant to denote; prefer a name that cannot be a class name, such as
  `db.replica`, for a service that is not an instance of its namesake. The
  narrowed return type is not a runtime guarantee.

- _IocContainerFactory_ every call to `newContainer()` MUST return a new
  container. A new container does not necessarily contain new service
  instances.

- Added research on service names, and on whether "has" and "get" agree.

- Added Q&A: why offer an _IocContainerFactory_, why throw _IocThrowable_
  for every failure, and why a class-string name doesn't guarantee an
  instance of it.

- Converted README generation to Stardoc, sourcing docs text from the
  interface docblocks instead of a separate file list.

- Meta-files refresh, CI workflow, typo fixes, and code hygiene updates.

## 1.0.0-alpha1

Incorporated indications from private review, and first public release.

- Added to researched projects.

- Corrected earlier research.

- _IocContainer_ no longer affords `newService()`.

- Ioc-Interop no longer handles service management:

    - Extracted service-related interfaces to Service-Interop.

    - Extracted resolver-related interfaces to Resolver-Interop.

## 1.0.0-dev1

Initial release for private review.
