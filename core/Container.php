<?php

namespace Core;

use Closure;
use ReflectionClass;
use ReflectionParameter;

class Container {
	protected array $bindings = [];
	protected array $instances = [];

	/**
	 * Bind a given type into the container.
	 *
	 * @param string $abstract The abstract type or identifier.
	 * @param Closure|string|null $concrete The concrete type, Closure that returns an instance, or null.
	 * @param bool $shared Determines if the binding is shared.
	 */
	public function bind(string $abstract, Closure|string $concrete = null, bool $shared = false): void {
		if ($concrete === null) {
			$concrete = $abstract;
		}
		$this->bindings[$abstract] = compact('concrete', 'shared');
	}

	/**
	 * Resolve a given type from the container.
	 *
	 * @param string $abstract The abstract type or identifier.
	 * @return mixed An instance of the requested type.
	 */
	public function get(string $abstract) {
		if (isset($this->instances[$abstract])) {
			return $this->instances[$abstract];
		}

		return $this->make($abstract);
	}

	/**
	 * Make or resolve a given type from the container.
	 *
	 * @param string $abstract The abstract type or identifier.
	 * @param array $parameters Optional parameters to pass to the constructor.
	 * @return mixed An instance of the requested type.
	 */
	public function make(string $abstract, array $parameters = []): mixed {
		if (!isset($this->bindings[$abstract])) {
			return $this->build($abstract, $parameters);
		}

		$concrete = $this->bindings[$abstract]['concrete'];
		$shared = $this->bindings[$abstract]['shared'];

		if ($shared && isset($this->instances[$abstract])) {
			return $this->instances[$abstract];
		}

		$object = $concrete instanceof Closure ? $concrete($this, $parameters) : $this->build($concrete, $parameters);

		if ($shared) {
			$this->instances[$abstract] = $object;
		}

		return $object;
	}

	/**
	 * Instantiate given type with the given params
	 *
	 * @param Closure|string $concrete The concrete type or Closure returns an instance of the concrete type.
	 * @param array $parameters Optional parameters to pass to the constructor of the concrete type.
	 * @return mixed An instance of the requested type.
	 * @throws \Exception
	 */
	protected function build(Closure|string $concrete, array $parameters = []): mixed {
		if ($concrete instanceof Closure) {
			return $concrete($this, $parameters);
		}

		$reflector = new ReflectionClass($concrete);
		if (!$reflector->isInstantiable()) {
			throw new \Exception("Class {$concrete} is not instantiable");
		}

		$constructor = $reflector->getConstructor();
		if (is_null($constructor)) {
			return new $concrete();
		}

		$dependencies = $constructor->getParameters();
		$instances = $this->resolveDependencies($dependencies);

		return $reflector->newInstanceArgs($instances);
	}

	/**
	 * Resolve all the dependencies for a method.
	 *
	 * @param array $dependencies The dependencies of the method.
	 * @return array An array of instances of the dependencies.
	 */
	protected function resolveDependencies(array $dependencies): array {
		$results = [];

		foreach ($dependencies as $dependency) {
			// Attempt to resolve the class of the dependency.
			$type = $dependency->getType();
			if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
				throw new \Exception("Can't resolve dependency {$dependency->name}");
			}

			$results[] = $this->get($type->getName());
		}

		return $results;
	}
}
