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

use ShinePress\Framework\Module;
use WeakMap;

class Registry
{
    /** @var WeakMap<Module, array<string, string[]>> */
    private static WeakMap $signatures;

    public static function add(Module $module, string $type, string $name): void
    {
        self::init();

        if (!isset(self::$signatures[$module]) || !is_array(self::$signatures[$module])) {
            self::$signatures[$module] = [];
        }

        if (!isset(self::$signatures[$module][$type]) || !is_array(self::$signatures[$module][$type])) {
            self::$signatures[$module][$type] = [];
        }

        self::$signatures[$module][$type][] = $name;
    }

    /**
     * @return array<string, string[]>
     */
    public static function get(Module $module): array
    {
        self::init();

        if (isset(self::$signatures[$module])) {
            return self::$signatures[$module];
        }

        return [];
    }

    private static function init(): void
    {
        if (!isset(self::$signatures)) {
            self::$signatures = new WeakMap();
        }
    }
}
