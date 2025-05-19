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
use ReflectionMethod;
use ShinePress\Framework\Attribute\MethodAttributeInterface;
use ShinePress\Framework\Module;
use ShinePress\Framework\Tests\Registration\Registry;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class MethodAttribute implements MethodAttributeInterface {
	public function register(Module $module, ReflectionMethod $method): void {
		Registry::add($module, 'method', $method->getName());
	}
}
