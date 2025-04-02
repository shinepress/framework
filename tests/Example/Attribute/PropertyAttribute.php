<?php

/*
 * This file is part of ShinePress.
 *
 * (c) Shine United LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ShinePress\Framework\Tests\Example\Attribute;

use Attribute;
use ReflectionProperty;
use ShinePress\Framework\Module;
use ShinePress\Framework\PropertyAttributeInterface;
use ShinePress\Framework\Tests\Registration\Registry;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class PropertyAttribute implements PropertyAttributeInterface {
	public function register(Module $module, ReflectionProperty $property): void {
		Registry::add($module, 'property', $property->getName());
	}
}
