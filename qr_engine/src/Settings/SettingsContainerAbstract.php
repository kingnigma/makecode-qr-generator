<?php

/**
 * Minimal Settings Container Abstract Class
 * Stub for chillerlan/php-settings-container
 */

namespace chillerlan\Settings;

use ArrayIterator;
use Traversable;

abstract class SettingsContainerAbstract implements SettingsContainerInterface
{

    /**
     * Constructor accepts an array of settings or another SettingsContainerInterface to populate the object
     */
    public function __construct(array|SettingsContainerInterface $settings = [])
    {
        // If it's already a SettingsContainerInterface, copy its properties
        if ($settings instanceof SettingsContainerInterface) {
            $this->fromIterable($settings);
        } else if (!empty($settings)) {
            // Use reflection to set properties from the array
            $this->fromArray($settings);
        }
    }

    /**
     * Populate object properties from array
     */
    protected function fromArray(array $settings): void
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if (isset($settings[$propertyName])) {
                // Use reflection to set protected properties
                $property->setAccessible(true);
                $property->setValue($this, $settings[$propertyName]);
            }
        }
    }

    /**
     * Populate object properties from iterable (SettingsContainerInterface)
     */
    protected function fromIterable(iterable $settings): void
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            // For SettingsContainerInterface, we need to iterate through it
            $found = false;
            foreach ($settings as $key => $value) {
                if ($key === $propertyName) {
                    $property->setAccessible(true);
                    $property->setValue($this, $value);
                    $found = true;
                    break;
                }
            }
        }
    }

    /**
     * Make the object iterable
     */
    public function getIterator(): Traversable
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties();
        $data = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $data[$property->getName()] = $property->getValue($this);
        }

        return new ArrayIterator($data);
    }

    /**
     * Magic getter for properties
     */
    public function __get(string $name)
    {
        $reflection = new \ReflectionClass($this);

        if ($reflection->hasProperty($name)) {
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            return $property->getValue($this);
        }

        throw new \Exception("Property '$name' does not exist");
    }

    /**
     * Magic setter for properties
     */
    public function __set(string $name, $value): void
    {
        $reflection = new \ReflectionClass($this);

        if ($reflection->hasProperty($name)) {
            $property = $reflection->getProperty($name);
            $property->setAccessible(true);
            $property->setValue($this, $value);
            return;
        }

        throw new \Exception("Property '$name' does not exist or is not settable");
    }
}
