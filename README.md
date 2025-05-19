# shinepress/framework

[![License](https://img.shields.io/packagist/l/shinepress/framework)](https://github.com/shinepress/framework/blob/main/LICENSE)
[![Latest Version](https://img.shields.io/packagist/v/shinepress/framework?label=latest)](https://packagist.org/packages/shinepress/framework/)
[![PHP Version](https://img.shields.io/packagist/dependency-v/shinepress/framework/php?label=php)](https://www.php.net/releases/index.php)
[![Main Status](https://img.shields.io/github/actions/workflow/status/shinepress/framework/verify.yml?branch=main&label=main)](https://github.com/shinepress/framework/actions/workflows/verify.yml?query=branch%3Amain)
[![Release Status](https://img.shields.io/github/actions/workflow/status/shinepress/framework/verify.yml?branch=release&label=release)](https://github.com/shinepress/framework/actions/workflows/verify.yml?query=branch%3Arelease)
[![Develop Status](https://img.shields.io/github/actions/workflow/status/shinepress/framework/verify.yml?branch=develop&label=develop)](https://github.com/shinepress/framework/actions/workflows/verify.yml?query=branch%3Adevelop)


## Description

A framework for creating Wordpress plugins and themes.


## Installation

The recommendend installation method is with composer:
```sh
$ composer require shinepress/framework
```


## Usage

The core of the framework is the Module class, create registerable modules for plugins and themes by extending it. The configure/initialize/finalize hooks can be overriden to run at specific times.

```php
use ShinePress\Framework\Module;

class MyModule extends Module {
	
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

// register the module
MyModule::register();
```


### Attributes

The primary purpose of the framework is to allow the configuration of a plugin/theme component using attributes.

Note: this is an example only, for actions and filters the [shinepress/hooks](https://packagist.org/packages/shinepress/hooks/) package is recommended.

```php

use ShinePress\Framework\Attribute\MethodAttributeInterface;
use ShinePress\Framework\Module;
use Attribute;
use ReflectionMethod;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class ActionHook implements MethodAttributeInterface {
	private string $name;
	private int $priority;

	public function __construct(string $name, int $priority = 10) {
		$this->name = $name;
		$this->priority = $priority;
	}

	public function register(Module $module, ReflectionMethod $method): void;
		add_action(
			$this->name,
			[$module, $method->getName()],
			$this->priority,
			$method->getNumberOfParameters(),
		);
	}
}

class MyModule extends Module {

	#[ActionHook('save_post', 20)]
	public function onSavePost($post_id, $post, $update): void {
		// do something...
	}
}

MyModule::register();
```
