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

namespace ShinePress\Framework\Tests\Example\Module;

use ShinePress\Framework\Module;
use ShinePress\Framework\Tests\Example\Attribute\ConstantAttribute;
use ShinePress\Framework\Tests\Example\Attribute\MethodAttribute;
use ShinePress\Framework\Tests\Example\Attribute\ObjectAttribute;
use ShinePress\Framework\Tests\Example\Attribute\PropertyAttribute;

#[ObjectAttribute]
#[ObjectAttribute]
#[ObjectAttribute]
class DuplicateModule extends Module
{
    #[ConstantAttribute]
    #[ConstantAttribute]
    #[ConstantAttribute]
    public const PUBLIC_CONSTANT = 'public';

    #[PropertyAttribute]
    #[PropertyAttribute]
    #[PropertyAttribute]
    public mixed $publicProperty;

    #[MethodAttribute]
    #[MethodAttribute]
    #[MethodAttribute]
    public function publicMethod(): void {}
}
