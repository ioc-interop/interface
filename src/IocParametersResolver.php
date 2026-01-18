<?php
declare(strict_types=1);

namespace IocInterop\Interface;

use ReflectionParameter;

/**
 * The [_IocParametersResolver_][] interface affords obtaining an array of
 * arguments for an array of parameters.
 */
interface IocParametersResolver
{
    /**
     * Resolves an array of parameters to an array of named parameter arguments,
     * allowing for an array of override arguments.
     *
     * - Notes:
     *
     *     - **TBD** Only resolve arguments for parameters names not present in
     *       the $args array keys.
     *
     * @param ReflectionParameter[] $parameters
     * @param array<string,mixed> $args
     * @return array<string,mixed>
     */
    public function resolveParameters(
        IocContainer $ioc,
        array $parameters,
        array $args = []
    ) : array;
}
