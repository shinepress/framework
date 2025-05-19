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

namespace ShinePress\Framework\Attribute;

use ReflectionObject;
use ShinePress\Framework\Module;

interface ObjectAttributeInterface {
	public function register(Module $module, ReflectionObject $object): void;
}
