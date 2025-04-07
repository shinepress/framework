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

namespace ShinePress\Framework\Tests\Registration;

use PHPUnit\Framework\Assert;
use ShinePress\Framework\Module;

class Signature
{
    /** @var array<string, string[]> */
    private array $expected = [];

    public function add(string $type, string $name): void
    {
        if (!isset($this->expected[$type]) || !is_array($this->expected[$type])) {
            $this->expected[$type] = [];
        }

        $this->expected[$type][] = $name;
    }

    public function assert(Module $module): void
    {
        $actual = Registry::get($module);

        $message = sprintf('expected %d types, found %d instead', count($this->expected), count($actual));
        Assert::assertSame(count($this->expected), count($actual), $message);

        foreach (array_keys($this->expected) as $key) {
            $message = sprintf('expected type "%s" not found in result', $key);
            Assert::assertTrue(isset($actual[$key]), $message);
        }

        foreach (array_keys($actual) as $key) {
            $message = sprintf('unexpected type "%s" found in result', $key);
            Assert::assertTrue(isset($this->expected[$key]), $message);
        }

        foreach ($this->expected as $type => $names) {
            $message = sprintf('signature type "%s" mismatch', $type);
            Assert::assertEqualsCanonicalizing($names, $actual[$type], $message);
        }
    }
}
