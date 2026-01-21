<?php
declare(strict_types=1);

namespace IocInterop\Interface;

use Throwable;

/**
 * [_IocThrowable_][] extends [_Throwable_][] to mark an [_Exception_][] as
 * IOC-related. It adds no class members.
 */
interface IocThrowable extends Throwable
{
}
