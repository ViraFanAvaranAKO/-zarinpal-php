<?php

namespace Ako\Zarinpal\Php\Helpers;

use Ako\Zarinpal\Php\Abstracts\BaseModel;

class Utils
{
    const UNIQUE_SYMBOL = "AKO::" . PHP_EOL . PHP_EOL . PHP_EOL . "::AKO";

    public static function array_merge_by_reference(array $referenceArray, array $overrideArray): array
    {
        $resultArray = [];
        foreach ($referenceArray as $key => $value) {
            $resultArray[$key] = isset($overrideArray[$key]) ? $overrideArray[$key] : $value;
        }
        return $resultArray;
    }

    public static function array_to_object_deep(array $array): object
    {
        $object = (object) $array;
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $object->$key = self::array_to_object_deep($value);
            }
        }
        return $object;
    }

    public static function as_enum_or_null(string $enum_class, $value)
    {
        $class = "";
        try {
            $class = get_class($value);
        } catch (\Throwable $th) {
            try {
                $value = $enum_class::from($value);
                $class = get_class($value);
            } catch (\Throwable $th) {
            }
        }
        if ($class != $enum_class) {
            return null;
        }
        return $value;
    }

    public static function is_model_instance($instance, ?string $model = null)
    {
        try {
            $type = get_class($instance);
            return static::is_model($type, $model);
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function is_model(string $type, ?string $model = null)
    {
        if ($model) {
            try {
                return $type == $model && $model == BaseModel::class || is_subclass_of($model, BaseModel::class);
            } catch (\Throwable $th) {
                return false;
            }
        }
        try {
            return $type == BaseModel::class || is_subclass_of($type, BaseModel::class);
        } catch (\Throwable $th) {
            return false;
        }
    }
    public static function get_full_default_selection(string $model, string $prefix = "")
    {
        if (!static::is_model($model)) {
            throw new \InvalidArgumentException("Provided value is not a Zarinpal model.");
        }
        $fields = $model::getFields();
        $relations = $model::getRelations();
        $eagers = $model::getEagerLoads();
        $result = array_keys($fields);
        foreach ($eagers as $eager) {
            array_push($result, ...static::get_full_default_selection($relations[$eager]->model, $eager));
        }
        return array_map(fn ($item) => $prefix == "" ? $item : ($prefix . '.' . $item), $result);
    }
}
