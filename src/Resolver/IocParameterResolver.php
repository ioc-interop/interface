<?php
declare(strict_types=1);

namespace IocInterop\Interface\Resolver;

use IocInterop\Interface\IocContainer;
use ReflectionParameter;

/**
 * [_IocParameterResolver_][] affords resolving a parameter to an argument
 * value.
 *
 * - Directives:
 *
 *     - Implementations MUST resolve parameters in this order:
 *
 *         - If the parameter has an [_Attribute_][] that implements
 *           [_IocParameterResolver_][], implementations MUST resolve the
 *           parameter using that attribute.
 *
 *         - Otherwise, if the parameter type is a [_ReflectionNamedType_][],
 *           and the container has a service for that type, implementations MUST
 *           resolve the parameter to that service.
 *
 *         - Otherwise, implementations MAY attempt to resolve the parameter
 *           using implementation-specific logic; such logic is expressly not
 *           defined herein.
 *
 *         - Otherwise, if the parameter has a default value, implementations
 *           MUST resolve the parameter to that value.
 *
 *     - Implementations MUST throw [_IocThrowable_][] if the parameter cannot
 *       be resolved.
 *
 * - Notes:
 *
 *     - **This interface can be implemented as an attribute.** Doing so allows
 *       implementors to define custom resolution approaches for consumers to
 *       apply to specific parameters. For example, implementors may declare a
 *       `#[GetEnv($name)]` attribute to resolve the parameter to an environment
 *       value.
 */
interface IocParameterResolver
{
    /**
     * Resolves the parameter to an argument value.
     *
     * - Notes:
     *
     *     - **The return is `mixed`.** The resolved value might be anything at
     *       at all. This allows (e.g.) attribute implementations to obtain a
     *       service from the container, and then obtain a value from that
     *       service.
     */
    public function resolveParameter(
        IocContainer $ioc,
        ReflectionParameter $parameter,
    ) : mixed;
}
