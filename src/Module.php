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

namespace ShinePress\Framework;

use ReflectionAttribute;
use ReflectionObject;
use ShinePress\Framework\Attribute\ConstantAttributeInterface;
use ShinePress\Framework\Attribute\MethodAttributeInterface;
use ShinePress\Framework\Attribute\ObjectAttributeInterface;
use ShinePress\Framework\Attribute\PropertyAttributeInterface;
use ShinePress\Framework\Exception\DuplicateInstanceException;

abstract class Module {
	/** @var array<class-string<self>, self> */
	private static array $instances = [];

	private bool $registered = false;

	/**
	 * @throws DuplicateInstanceException
	 */
	public function __construct() {
		if (isset(self::$instances[static::class])) {
			throw new DuplicateInstanceException(vsprintf(
				'An instance of "%s" has already been created.',
				[
					static::class,
				],
			));
		}

		self::$instances[static::class] = $this;

		// run initialization hook
		$this->initialize();
	}

	final public static function instance(): static {
		$class = static::class;

		if (isset(self::$instances[$class]) && is_a(self::$instances[$class], $class, false)) {
			return self::$instances[$class];
		}

		return new $class();
	}

	final public static function register(?self $module = null): void {
		if (!$module instanceof self) {
			$module = static::instance();
		} elseif ($module !== self::$instances[$module::class]) {
			throw new DuplicateInstanceException(vsprintf(
				'Provided module is a duplicate instance of "%s".',
				[
					$module::class,
				],
			));
		}

		if ($module->registered) {
			// already registered
			return;
		}
		$module->registered = true;

		// run prepare hook
		$module->prepare();

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
		$module->cleanup();
	}

	protected function initialize(): void {
		// runs during constructor
	}

	protected function prepare(): void {
		// runs before registration
	}

	protected function cleanup(): void {
		// runs after registration
	}
}
