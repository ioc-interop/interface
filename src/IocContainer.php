<?php
declare(strict_types=1);

namespace IocInterop\Interface;

/**
 * [_IocContainer_][] affords obtaining services by name.
 *
 * - Notes:
 *
 *     - **This interface does not afford service management.** The container
 *       will need to create and retain services somehow, whether by itself,
 *       through a [Service-Interop][] implementation, or some other means.
 *
 * @phpstan-import-type ioc_service_name_string from IocTypeAliases
 * @phpstan-import-type ioc_service_object from IocTypeAliases
 */
interface IocContainer
{
    /**
     * Might the container return an object for the `$serviceName`?
     *
     * - Directives:
     *
     *     - Implementations MUST return `false` only when the container
     *       knows in advance that `getService()` will not be able to
     *       return an object for the `$serviceName`.
     *
     *     - Implementations MUST NOT produce a service, by instantiation or
     *       any other means, in order to answer.
     *
     * - Notes:
     *
     *     - **The logic for this method is expressly unspecified.** The check
     *       may be accomplished by querying a service management subsystem, or
     *       by some other means.
     *
     *     - **A `false` result is conclusive; a `true` result is not.** A
     *       `false` result means the container already knows it cannot
     *       produce an object for the `$serviceName`, so `getService()` will
     *       throw [_IocThrowable_][]. A `true` result means only that the
     *       container cannot tell in advance whether it will fail to
     *       produce the `$serviceName`; for example, failures from a
     *       missing dependency or bad configuration surface only on the
     *       attempt. Either answer speaks only to the call that produced
     *       it; a container whose state changes may answer differently
     *       next time.
     *
     *     - **Every path by which `getService()` could succeed needs a
     *       matching check here.** The directive binds this method to what
     *       `getService()` could do, and `getService()` might do a great
     *       deal: look in a registry, consult a service management
     *       subsystem, autowire from a class name, and so on. Adding a path to
     *       `getService()` without adding a corresponding check in
     *       `hasService()` leaves the container returning `false` for a
     *       service it can in fact produce.
     *
     * @param ioc_service_name_string $serviceName
     */
    public function hasService(string $serviceName) : bool;

    /**
     * Returns an object for the `$serviceName`.
     *
     * - Directives:
     *
     *     - Implementations MUST throw [_IocThrowable_][] if the container
     *       cannot return an object for the `$serviceName`, regardless of the
     *       underlying cause.
     *
     * - Notes:
     *
     *     - **The logic for this method is expressly unspecified.** Retrieval
     *       may be accomplished via a service management subsystem, or by some
     *       other means.
     *
     *     - **The service name is arbitrary, but it can determine the return
     *       type.** Any non-empty string will serve: `'db.replica'` is as
     *       valid a name as `\Foo\Bar::class`. However, when the name is a
     *       class-string, the `ioc_service_object` return type resolves to
     *       that class rather than to `object`.
     *
     *     - **The narrowed return type is not a runtime guarantee.**
     *       Nothing requires a service to be an instance of the class its
     *       name denotes; the narrowing is what static analysis infers,
     *       not what the container promises. A caller that needs the
     *       guarantee has `instanceof` for it.
     *
     *     - **Static analysis treats a name that could be a class name as
     *       one.** It looks for the class, not for the intent behind the
     *       label: `'logger'` is a class-string wherever a `Logger` class
     *       exists, case notwithstanding. Where a service is not an
     *       instance of its namesake, a name that cannot be a class name
     *       avoids the narrowing; a dot, as in `'db.replica'`, is enough.
     *
     *     - **The returned instance may be new or shared.** The retrieval
     *       logic defines the service lifetime, not the container (per se) and
     *       not the caller requesting the service.
     *
     *     - **Make the [_IocThrowable_][] as informative as possible.** Doing so
     *       helps to debug why the call failed. For one example, if the logic
     *       for producing an object results in an [_Error_][] or
     *       [_Exception_][], consider setting that as the `$previous`
     *       [_Throwable_][] for the [_IocThrowable_][]. For another example,
     *       consider describing the resolution path in the message itself to
     *       show where a circular or deeply-nested resolution failed.
     *
     * @param ioc_service_name_string $serviceName
     * @return ioc_service_object
     */
    public function getService(string $serviceName) : object;
}
