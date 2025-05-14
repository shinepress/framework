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

namespace ShinePress\Framework\Tests\Example\Module;

use ShinePress\Framework\Module;
use ShinePress\Framework\Tests\Example\Attribute\MethodAttribute;

class SingleAttributeModule extends Module {
	#[MethodAttribute]
	public function singleMethod(): void {
		// do nothing
	}
}
