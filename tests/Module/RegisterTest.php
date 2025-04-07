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

namespace ShinePress\Framework\Tests\Module;

use ShinePress\Framework\Module;
use ShinePress\Framework\Tests\Example\Module\AttributelessModule;
use ShinePress\Framework\Tests\Example\Module\DuplicateModule;
use ShinePress\Framework\Tests\Example\Module\SimpleModule;
use ShinePress\Framework\Tests\Example\Module\SingleAttributeModule;
use ShinePress\Framework\Tests\Registration\Signature;
use ShinePress\Framework\Tests\TestCase;

class RegisterTest extends TestCase
{
    public function testSimpleModule(): void
    {
        $signature = new Signature();
        $signature->add('object', SimpleModule::class);
        $signature->add('constant', 'PUBLIC_CONSTANT');
        $signature->add('constant', 'PROTECTED_CONSTANT');
        $signature->add('constant', 'PRIVATE_CONSTANT');
        $signature->add('property', 'publicProperty');
        $signature->add('property', 'protectedProperty');
        $signature->add('property', 'privateProperty');
        $signature->add('method', 'publicMethod');
        $signature->add('method', 'protectedMethod');
        $signature->add('method', 'privateMethod');

        SimpleModule::register();

        $module = SimpleModule::instance();

        $signature->assert($module);
    }

    public function testDuplicateModule(): void
    {
        $signature = new Signature();
        $signature->add('object', DuplicateModule::class);
        $signature->add('object', DuplicateModule::class);
        $signature->add('object', DuplicateModule::class);
        $signature->add('constant', 'PUBLIC_CONSTANT');
        $signature->add('constant', 'PUBLIC_CONSTANT');
        $signature->add('constant', 'PUBLIC_CONSTANT');
        $signature->add('property', 'publicProperty');
        $signature->add('property', 'publicProperty');
        $signature->add('property', 'publicProperty');
        $signature->add('method', 'publicMethod');
        $signature->add('method', 'publicMethod');
        $signature->add('method', 'publicMethod');

        DuplicateModule::register();

        $module = DuplicateModule::instance();

        $signature->assert($module);
    }

    public function testAttributelessModule(): void
    {
        $signature = new Signature();

        AttributelessModule::register();

        $module = AttributelessModule::instance();

        $signature->assert($module);
    }

    public function testDirectRegistration(): void
    {
        $signature = new Signature();
        $signature->add('method', 'singleMethod');

        $module = SingleAttributeModule::instance();

        Module::register($module);

        $signature->assert($module);
    }

    public function testDoubleRegistration(): void
    {
        $signature = new Signature();
        $signature->add('method', 'singleMethod');

        SingleAttributeModule::register();
        SingleAttributeModule::register(); // second registration should not matter

        $module = SingleAttributeModule::instance();
        Module::register($module); // nor should additional direct registration

        $signature->assert($module);
    }
}
