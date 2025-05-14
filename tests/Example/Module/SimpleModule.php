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
use ShinePress\Framework\Tests\Example\Attribute\ConstantAttribute;
use ShinePress\Framework\Tests\Example\Attribute\MethodAttribute;
use ShinePress\Framework\Tests\Example\Attribute\ObjectAttribute;
use ShinePress\Framework\Tests\Example\Attribute\PropertyAttribute;

#[ObjectAttribute]
class SimpleModule extends Module {
	#[ConstantAttribute]
	public const PUBLIC_CONSTANT = 'public';

	#[ConstantAttribute]
	protected const PROTECTED_CONSTANT = 'protected';

	#[ConstantAttribute]
	private const PRIVATE_CONSTANT = 'private'; // @phpstan-ignore classConstant.unused

	#[PropertyAttribute]
	public mixed $publicProperty;

	#[PropertyAttribute]
	protected mixed $protectedProperty;

	#[PropertyAttribute]
	private mixed $privateProperty; // @phpstan-ignore property.unused

	#[MethodAttribute]
	public function publicMethod(): void {}

	#[MethodAttribute]
	protected function protectedMethod(): void {}

	// @phpstan-ignore method.unused
	#[MethodAttribute]
	private function privateMethod(): void {}
}
