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
use ReflectionClassConstant;
use ShinePress\Framework\ConstantAttributeInterface;
use ShinePress\Framework\Module;
use ShinePress\Framework\Tests\Registration\Registry;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
class ConstantAttribute implements ConstantAttributeInterface
{
    public function register(Module $module, ReflectionClassConstant $constant): void
    {
        Registry::add($module, 'constant', $constant->getName());
    }
}
