<?php

namespace FalconBaseServices\Services;

use Closure;
use FalconBaseServices\Providers\ServiceProvider;
use FalconBaseServices\Singleton;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class FalconContainer extends Singleton implements ContainerInterface
{
    private array $bindings = [];
    private array $instances = [];

    public function singleton(string $id, string|null|\Closure $concrete = null): void
    {
        $this->bind($id, $concrete, true);
    }

    public function bind(string $id, string|null|\Closure $concrete = null, bool $shared = false): void
    {
        if (!$this->has($id)) {
            $this->bindings[$id] = ['concrete' => $concrete ?? $id, 'shared' => $shared];
        }
    }

    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->bindings);
    }

    public function make(string $id)
    {
        // Handle bindings
        if (isset($this->bindings[$id])) {
            return $this->resolve($this->bindings[$id]['concrete']);
        }

        // Handle non-bindings
        return $this->resolve($id);
    }

    /**
     * @throws ReflectionException
     */
    protected function resolve(string|\Closure $concrete)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()) {
            throw new ReflectionException("Class $concrete is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if (\is_null($constructor)) {
            return new $concrete;
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    protected function getDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencyClass = $parameter->getType();
            if ($dependencyClass && !$dependencyClass->isBuiltin()) {
                $dependencies[] = $this->get($dependencyClass->getName());
            } else {
                $dependencies[] = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;
            }
        }

        return $dependencies;
    }

    public function get(string $id)
    {
        // Handle singleton instances
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        } else {
            // Handle non-bindings
            if (!$this->has($id)) {
                if (!\class_exists($id)) {
                    return null;
                } else {
                    try {
                        return $this->resolve($id);
                    } catch (ReflectionException $exception) {
                        dd($exception->getMessage());
                    }
                }
            }
        }

        // Handle bindings
        try {
            $resolved = $this->resolve($this->bindings[$id]['concrete']);
        } catch (ReflectionException $exception) {
            dd($exception->getMessage());
        }

        if ($this->shared($id))
            $this->instances[$id] = $resolved;

        return $resolved;
    }

    protected function shared($id)
    {
        return $this->bindings[$id]['shared'];
    }

    public function getMethod($class, $method)
    {
        $object = $this->get($class);
        $reflectionMethod = new ReflectionMethod($object, $method);
        $parameters = $reflectionMethod->getParameters();
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencyClass = $parameter->getType(); // Updated to getType() for PHP 8.2
            if ($dependencyClass && !$dependencyClass->isBuiltin()) { // Check if it's a class and not a built-in type
                $dependencies[] = $this->get($dependencyClass->getName());
            }
        }

        return $reflectionMethod->invokeArgs($object, $dependencies);
    }

    public function runProviders()
    {
        $providers = require_once __DIR__ . '/../../bootstrap/providers.php';
        $provider_instances = [];

        foreach ($providers as $provider) {
            if (\is_subclass_of($provider, ServiceProvider::class))
                $provider_instances[$provider] = new $provider($this);
        }

        foreach ($provider_instances as $instance) {
            $instance->register();
        }

        foreach ($provider_instances as $instance) {
            $instance->boot();
        }
    }

}
