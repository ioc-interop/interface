<?php
declare(strict_types=1);

namespace IocInterop\Interface;

use ReflectionParameter;

/**
 * The [_IocParameterResolver_][] interface affords obtaining an argument for a
 * parameter.
 *
 * - Notes:
 *
 *     - **TBD** Have attribute implement this, then reflection logic can call
 *       `newInstance()->resolveParameter($ioc, $parameter)` to get back attribute value.
 *       E.g. `#[Inject(Foo::class)]` on a constructor parameter for a resolver
 *       to handle, or on a property for a builder to handle, etc.
 */
interface IocParameterResolver
{
    /**
     * - Notes:
     *
     *     - **TBD** Mixed, not object, as some attributes may be used for
     *       resolving non-object values, e.g. by pulling a container service
     *       and returning a value from it.
     */
    public function resolveParameter(
        IocContainer $ioc,
        ReflectionParameter $parameter,
    ) : mixed;
}
