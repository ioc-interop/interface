<?php
declare(strict_types=1);

namespace IocInterop\Interface\Resolver;

use IocInterop\Interface\IocContainer;
use ReflectionParameter;

/**
 * [_IocParametersResolver_][] affords resolving an array of parameters to an
 * array of named arguments.
 */
interface IocParametersResolver
{
    /**
     * Resolves an array of parameters to an array of named parameter arguments,
     * allowing for an array of override arguments.
     *
     * - Directives:
     *
     *     - Implementations MUST NOT attempt to resolve parameters that already
     *       exist by name in the `$arguments` array keys.
     *
     *     - When resolving a parameter, implementations MUST do so using logic
     *       equivalent to that specified by [_IocParameterResolver_][].
     *
     *     - Implementations MUST return an array of arguments keyed by the
     *       parameter names.
     *
     * - Notes:
     *
     *     - **Do not replace existing `$arguments`.** If an argument has
     *       already been given for a parameter name, there is no need to
     *       resolve the related parameter.
     *
     * @param ReflectionParameter[] $parameters
     * @param mixed[] $arguments
     * @return mixed[]
     */
    public function resolveParameters(
        IocContainer $ioc,
        array $parameters,
        array $arguments = []
    ) : array;
}
