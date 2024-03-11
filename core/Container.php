<?php

namespace Core;

use Closure;

/**
 * Class Container
 *
 * The Container responsible for managing class dependencies and performing DI.
 * It binds abstract types to concrete types and then resolves
 * these abstract types, automatically injecting any dependencies.
 */
class Container
{
	protected array $bindings = [];

	/**
	 * Bind a given type into the container.
	 *
	 * @param string $abstract The abstract type or identifier.
	 * @param string|Closure|null $concrete The concrete type or Closure that returns an instance of the concrete type.
	 * @param bool $shared Determines if the binding is shared - i.e., if the same instance should be returned on subsequent resolutions.
	 */
	public function bind(string $abstract, object $concrete = null, bool $shared = false): void
	{
		if (is_null($concrete)) {
			$concrete = $abstract;
		}
		$this->bindings[$abstract] = compact('concrete', 'shared');
	}

	/**
	 * Resolve a given type from the container.
	 *
	 * @param string $abstract The abstract type or identifier.
	 * @param array $parameters Optional parameters to pass to the constructor of the concrete type.
	 * @return mixed An instance of the requested type.
	 */
	public function make(string $abstract, array $parameters = []): mixed
	{
		if (!isset($this->bindings[$abstract])) {
			return $this->build($abstract, $parameters);
		}

		$concrete = $this->bindings[$abstract]['concrete'];
		$shared = $this->bindings[$abstract]['shared'];
		if ($shared && isset($this->instances[$abstract])) {
			return $this->instances[$abstract];
		}

		$object = $this->build($concrete, $parameters);
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
	protected function build(Closure|string $concrete, array $parameters): mixed
	{
		if ($concrete instanceof Closure) {
			return $concrete($this, $parameters);
		}

		try {
			$reflector = new \ReflectionClass($concrete);
		} catch (\ReflectionException $e) {
			throw new \Exception("Class {$concrete} does not exist");
		}
		if (!$reflector->isInstantiable()) {
			throw new \Exception("Class {$concrete} is not instantiable");
		}

		$constructor = $reflector->getConstructor();
		if (is_null($constructor)) {
			return new $concrete;
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
	protected function resolveDependencies(array $dependencies): array
	{
		$results = [];
		foreach ($dependencies as $dependency) {
			$results[] = $this->make($dependency->name);
		}

		return $results;
	}
}