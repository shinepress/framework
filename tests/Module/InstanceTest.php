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

namespace ShinePress\Framework\Tests\Module;

use Error;
use ShinePress\Framework\Module;
use ShinePress\Framework\Tests\Example\Module\AttributelessModule;
use ShinePress\Framework\Tests\TestCase;

class InstanceTest extends TestCase {
	public function testStaticInstantiation(): void {
		$module = AttributelessModule::instance();

		// should be an instance of Module
		self::assertInstanceOf(Module::class, $module);

		// should be an instance of requested module
		self::assertInstanceOf(AttributelessModule::class, $module);

		// should be exactly the requested module
		self::assertSame(AttributelessModule::class, $module::class);

		// when requested a second time, instance() should return the exact same instance
		self::assertSame($module, AttributelessModule::instance());
	}

	public function testDirectInstantiation(): void {
		// direct instantiation is not allowed
		self::expectException(Error::class);
		$module = new AttributelessModule(); // @phpstan-ignore new.privateConstructor
	}
}
