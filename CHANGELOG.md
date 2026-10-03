# Change Log

## 1.0.0-alpha2

Refinements from continued review and research:

- BC break: service names must be non-empty. `ioc_service_name_string` is
  now `non-empty-string`.

- _IocContainer_ `getService()` MUST throw _IocThrowable_ for any failure,
  not only for an unrecognized service name, and MUST retain a causing
  _Error_ or _Exception_ as the previous exception.

- _IocContainer_ `getService()` returns an object *for* the service name,
  rather than an instance *of* it.

- _IocContainer_ `getService()` warns that any name matching an existing
  class is treated as a class-string by static analysis, whatever the name
  was meant to denote; prefer a name that cannot be a class name, such as
  `db.replica`, for a service that is not an instance of its namesake. The
  narrowed return type is not a runtime guarantee.

- _IocContainer_ `hasService()` MUST return `false` only when the container
  knows in advance that `getService()` will not be able to return an object
  for the service name, and MUST NOT produce a service, by instantiation or
  any other means, in order to answer. A `false` result therefore promises
  that `getService()` will throw _IocThrowable_.

- _IocContainer_ `hasService()` asks whether the container *might* return an
  object for the service name; it no longer asks whether the container is
  able to return an instance *of* it. A `true` result promises nothing, and
  either result speaks only of the call that produced it.

- _IocContainerFactory_ every call to `newContainer()` MUST return a new
  container. A new container does not necessarily contain new service
  instances.

- Added research on service names, and on whether "has" and "get" agree.

- Added Q&A on offering _IocContainerFactory_, on throwing _IocThrowable_
  for every failure, and on class-string names not guaranteeing an instance.

- Convert to Stardoc for README generation by moving source text for docs
  into the interface docblocks. This reorganizes the README but leaves
  normative intent and meaning unchanged.

- Meta-files refresh, typo fixes, and code hygiene updates.

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
