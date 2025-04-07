<?php

/*
 * This file is part of ShinePress.
 *
 * (c) Shine United LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace ShinePress\Framework\Tests\Example\Attribute;

use Attribute;
use ReflectionObject;
use ShinePress\Framework\Module;
use ShinePress\Framework\ObjectAttributeInterface;
use ShinePress\Framework\Tests\Registration\Registry;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class ObjectAttribute implements ObjectAttributeInterface
{
    public function register(Module $module, ReflectionObject $method): void
    {
        Registry::add($module, 'object', $method->getName());
    }
}
