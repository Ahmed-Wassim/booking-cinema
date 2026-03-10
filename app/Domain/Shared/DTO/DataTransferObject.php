<?php

declare(strict_types=1);

namespace App\Domain\Shared\DTO;

use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{
    protected static array $allow_empty_value = [];

    protected static bool $is_updating = false;

    /**
     * @param  array<string,mixed>  $parameters
     *
     * @auther Mustafa Goda
     */
    public function __construct(array $parameters)
    {
        $class = new ReflectionClass(static::class);
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $property = $reflectionProperty->getName();
            $this->{$property} = $parameters[$property];
        }
    }

    /**
     * @auther Mustafa Goda
     *
     * @param  array<int, string>  $request
     * @return static
     */
    abstract public static function fromRequest(array $request): self;

    /**
     * @auther shaheen
     * used in case updating
     *
     * @param  array<int, string>  $request
     * @return static
     */
    public static function existFromRequest(array $request): array
    {
        self::$is_updating = true;
        $fields = (array) static::fromRequest($request);
        if ($statusField = self::isUpdatingOnlyStatus($fields)) {

            return $statusField;
        }

        return array_filter($fields, function ($value, $key) {

            return $value || in_array($key, static::$allow_empty_value);
        }, ARRAY_FILTER_USE_BOTH);
    }

    private static function isUpdatingOnlyStatus(array $fields)
    {

        $fields = array_filter($fields, fn ($value) => $value);

        return ((count($fields) == 1) && array_key_exists('status', $fields)) ? $fields : false;
    }
}
