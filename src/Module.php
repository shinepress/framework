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

namespace ShinePress\Framework;

use ReflectionAttribute;
use ReflectionObject;

abstract class Module
{
    /** @var array<class-string<self>, self> */
    private static array $instances = [];

    private bool $registered = false;

    private function __construct()
    {
        // run configuration hook
        $this->configure();
    }

    final public static function instance(): static
    {
        $class = static::class;

        if (!isset(self::$instances[$class]) || !is_a(self::$instances[$class], $class, false)) {
            self::$instances[$class] = new $class();
        }

        return self::$instances[$class];
    }

    final public static function register(?self $module = null): void
    {
        if (!$module instanceof self) {
            $module = static::instance();
        }

        if ($module->registered) {
            // already registered
            return;
        }
        $module->registered = true;

        // run initialization hook
        $module->initialize();

        $object = new ReflectionObject($module);

        $objectAttributes = $object->getAttributes(
            ObjectAttributeInterface::class,
            ReflectionAttribute::IS_INSTANCEOF,
        );

        foreach ($objectAttributes as $objectAttribute) {
            $attributeInstance = $objectAttribute->newInstance();

            $attributeInstance->register(
                $module,
                $object,
            );
        }

        $objectConstants = $object->getReflectionConstants();
        foreach ($objectConstants as $objectConstant) {
            if (defined(self::class . '::' . $objectConstant->getName())) {
                // skip constants declared here
                continue;
            }

            $constantAttributes = $objectConstant->getAttributes(
                ConstantAttributeInterface::class,
                ReflectionAttribute::IS_INSTANCEOF,
            );

            foreach ($constantAttributes as $constantAttribute) {
                $attributeInstance = $constantAttribute->newInstance();

                $attributeInstance->register(
                    $module,
                    $objectConstant,
                );
            }
        }

        $objectProperties = $object->getProperties();
        foreach ($objectProperties as $objectProperty) {
            if (property_exists(self::class, $objectProperty->getName())) {
                // skip properties declared here
                continue;
            }

            $propertyAttributes = $objectProperty->getAttributes(
                PropertyAttributeInterface::class,
                ReflectionAttribute::IS_INSTANCEOF,
            );

            foreach ($propertyAttributes as $propertyAttribute) {
                $attributeInstance = $propertyAttribute->newInstance();

                $attributeInstance->register(
                    $module,
                    $objectProperty,
                );
            }
        }

        $objectMethods = $object->getMethods();
        foreach ($objectMethods as $objectMethod) {
            if (method_exists(self::class, $objectMethod->getName())) {
                // skip methods declared here
                continue;
            }

            $methodAttributes = $objectMethod->getAttributes(
                MethodAttributeInterface::class,
                ReflectionAttribute::IS_INSTANCEOF,
            );

            foreach ($methodAttributes as $methodAttribute) {
                $attributeInstance = $methodAttribute->newInstance();

                $attributeInstance->register(
                    $module,
                    $objectMethod,
                );
            }
        }

        // run finalization hook
        $module->finalize();
    }

    protected function configure(): void
    {
        // runs during constructor
    }

    protected function initialize(): void
    {
        // runs before registration
    }

    protected function finalize(): void
    {
        // runs after registration
    }
}
