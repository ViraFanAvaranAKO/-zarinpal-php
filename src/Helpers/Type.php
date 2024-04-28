<?php

namespace Ako\Zarinpal\Php\Helpers;

use Ako\Zarinpal\Php\Exceptions\InvalidResponseTypeException;
use Carbon\Carbon;
use Exception;

class Type
{
    const NULL_TYPE = 0;
    const REQUIRED_TYPE = 1;

    const ID_TYPE = 2;
    const NUMBER_TYPE = 4;
    const INTEGER_TYPE = 8;
    const FLOAT_TYPE = 16;
    const STRING_TYPE = 32;
    const BOOLEAN_TYPE = 64;
    const DATE_TYPE = 128;
    const DATETIME_TYPE = 256;
    const ENUM_TYPE = 512;
    const MODEL_TYPE = 1024;
    const OBJECT_TYPE = 2048;
    const ARRAY_TYPE = 4096;
    const DYNAMIC_TYPE = 8192;

    public static function null()
    {
        return new Type(Type::NULL_TYPE);
    }
    public static function ID(bool $required = false)
    {
        return new Type(Type::ID_TYPE | ($required ? Type::REQUIRED_TYPE : 0));
    }
    public static function number(bool $required = false)
    {
        return new Type(Type::NUMBER_TYPE | ($required ? Type::REQUIRED_TYPE : 0));
    }
    public static function integer(bool $required = false)
    {
        return new Type(Type::NUMBER_TYPE | Type::INTEGER_TYPE | ($required ? Type::REQUIRED_TYPE : 0));
    }
    public static function float(bool $required = false)
    {
        return new Type(Type::NUMBER_TYPE | Type::FLOAT_TYPE | ($required ? Type::REQUIRED_TYPE : 0));
    }
    public static function string(bool $required = false)
    {
        return new Type(Type::STRING_TYPE | ($required ? Type::REQUIRED_TYPE : 0));
    }
    public static function bool(bool $required = false)
    {
        return new Type(Type::BOOLEAN_TYPE | ($required ? Type::REQUIRED_TYPE : 0));
    }
    public static function date(bool $required = false)
    {
        return new Type(Type::DATE_TYPE | ($required ? Type::REQUIRED_TYPE : 0));
    }
    public static function datetime(bool $required = false)
    {
        return new Type(Type::DATETIME_TYPE | ($required ? Type::REQUIRED_TYPE : 0));
    }
    public static function enum($enum_class, bool $required = false)
    {
        try {
            $is_enum = (new \ReflectionClass($enum_class))->isEnum();
            if (!$is_enum) {
                throw new \Exception();
            }
            return new Type(Type::ENUM_TYPE | ($required ? Type::REQUIRED_TYPE : 0), $enum_class);
        } catch (\Throwable $th) {
            throw new \InvalidArgumentException('Given type is not an enum');
        }
    }
    public static function model(string $model, bool $required = false)
    {
        if (Utils::is_model($model)) {
            return new Type(Type::MODEL_TYPE | ($required ? Type::REQUIRED_TYPE : 0), $model);
        }
        throw new \InvalidArgumentException('Given type is not a Zarinpal model');
    }

    public static function list(Type $items_type, bool $required = false)
    {
        return new Type(Type::ARRAY_TYPE | ($required ? Type::REQUIRED_TYPE : 0), $items_type);
    }

    public static function object(array $object, bool $required = false)
    {
        $non_string_keys = array_filter(array_keys($object), fn ($key) => !is_string($key));
        if (count($non_string_keys)) {
            throw new \InvalidArgumentException('Some keys in the object are not strings');
        }
        $non_type_values = array_filter($object, fn ($value) => !($value instanceof Type));
        if (count($non_type_values)) {
            throw new \InvalidArgumentException('Some values in the object are not instance of Type');
        }
        return new Type(Type::OBJECT_TYPE | ($required ? Type::REQUIRED_TYPE : 0), $object);
    }

    public static function dynamic(bool $required = false)
    {
        return new Type(Type::DYNAMIC_TYPE | ($required ? Type::REQUIRED_TYPE : 0));
    }

    public int $type;
    public $ref = null;
    protected function __construct(int $type, $ref = null)
    {
        $this->type = $type;
        $this->ref = $ref;
    }

    public function hasFlag(int $flag)
    {
        return ($this->type & $flag) === $flag;
    }

    protected static function guess_type($value): Type|null
    {
        if (is_null($value)) {
            return Type::null();
        }
        if (is_bool($value)) {
            return Type::bool();
        }
        if (is_numeric($value)) {
            return Type::number();
        }
        if (is_int($value)) {
            return Type::integer();
        }
        if (is_float($value)) {
            return Type::float();
        }
        if (is_string($value)) {
            return Type::string();
        }

        if ($value instanceof \BackedEnum) {
            $enum_class = get_class($value);
            return Type::enum($enum_class);
        }

        if (Utils::is_model_instance($value)) {
            $model_class = get_class($value);
            return Type::model($model_class);
        }

        if (is_object($value)) {
            try {
                $value = (array) $value;
            } catch (\Throwable $th) {
                // TODO: Non-Directly castable Objects
                return null;
            }
        }

        if (is_array($value)) {
            if (count($value) == 0) {
                return Type::list(Type::null());
            }

            $i = 0;
            $list = true;
            $res = [];
            $ref_type = null;
            foreach ($value as $key => $sub_value) {
                $res[$key] = static::guess_type($sub_value);
                $ref_type = $res[$key]?->type;
                if ($key !== $i) {
                    $list = false;
                }
                $i++;
            }
            // Check if non of $res array items are null
            if (count(array_filter($res, function ($item) {
                return $item === null;
            })) !== 0) {
                return null;
            }

            // Check if all $res array items have the same type with $ref_type
            if (count(array_filter($res, function ($item) use ($ref_type) {
                return $item->type !== $ref_type;
            })) !== 0) {
                return Type::object($res);
            }

            if ($list) {
                return Type::list($res[0]);
            }
            return Type::object($res);
        }

        return null;
    }
    public function toSelectionArray()
    {
        if ($this->hasFlag(Type::MODEL_TYPE)) {
            return Utils::get_full_default_selection($this->ref);
        }
        if ($this->hasFlag(Type::OBJECT_TYPE)) {
            $result = [];
            foreach ($this->ref as $key => $value) {
                $temp = $value->toSelectionArray();
                if (count($temp)) {
                    array_push($result, ...array_map(fn ($item) => $key . '.' . $item, $temp));
                } else {
                    $result[] = $key;
                }
            }
            return $result;
        }
        if ($this->hasFlag(Type::ARRAY_TYPE)) {
            return $this->ref->toSelectionArray();
        }
        return [];
    }
    public function castIn($value)
    {
        if (!$this->validate($value)) {
            throw new \InvalidArgumentException('Given value is not valid for this type');
        }

        if ($this->type === Type::NULL_TYPE) {
            return 'null';
        }

        if (is_null($value) && !$this->hasFlag(Type::REQUIRED_TYPE)) {
            return 'null';
        }

        if ($this->hasFlag(Type::DYNAMIC_TYPE)) {
            $type = static::guess_type($value);
            if ($type == null) {
                throw new \InvalidArgumentException('Given value is not of a supported type');
            }
            return $type->castIn($value);
        }

        if ($this->hasFlag(Type::ARRAY_TYPE)) {
            $arrString = '[';
            $first = true;
            foreach ($value as $element) {
                if ($first) {
                    $first = false;
                } else {
                    $arrString .= ', ';
                }
                $arrString .= $this->ref->castIn($element);
            }
            $arrString .= ']';
            return $arrString;
        } elseif ($this->hasFlag(Type::OBJECT_TYPE)) {
            if (is_object($value)) {
                $value = (array) $value;
            }
            $result = [];
            foreach ($this->ref as $key => $type) {
                $result[$key] = $type->castIn($value[$key]);
            }
            return json_encode($result);
        } else {
            if ($this->hasFlag(Type::ID_TYPE)) {
                return (string) $value;
            }
            if ($this->hasFlag(Type::NUMBER_TYPE)) {
                return (string) $value;
            }
            if ($this->hasFlag(Type::INTEGER_TYPE)) {
                return (string) $value;
            }
            if ($this->hasFlag(Type::FLOAT_TYPE)) {
                return (string) $value;
            }
            if ($this->hasFlag(Type::STRING_TYPE)) {
                $value = str_replace('"', '\"', $value);
                if (strpos($value, "\n") !== false) {
                    $value = '"""' . $value . '"""';
                } else {
                    $value = "\"$value\"";
                }
                return $value;
            }
            if ($this->hasFlag(Type::BOOLEAN_TYPE)) {
                if ($value) {
                    return 'true';
                } else {
                    return 'false';
                }
            }

            if ($this->hasFlag(Type::DATE_TYPE)) {
                $value = Carbon::parse($value);
                $value = $value->format('Y-m-d');
                return "\"$value\"";
            }

            if ($this->hasFlag(Type::DATETIME_TYPE)) {
                $value = Carbon::parse($value);
                $value = $value->format('Y-m-d H:i:s');
                return "\"$value\"";
            }
            if ($this->hasFlag(Type::ENUM_TYPE)) {
                return Utils::as_enum_or_null($this->ref, $value)->value;
            }

            if ($this->hasFlag(Type::MODEL_TYPE)) {
                return json_encode($value);
            }
        }
    }
    public function castOut($value)
    {
        try {
            if ($this->type === Type::NULL_TYPE) {
                return null;
            }
            if ($this->hasFlag(Type::DYNAMIC_TYPE)) {
                return $value;
            }
            if ($this->hasFlag(Type::MODEL_TYPE)) {
                $cls = $this->ref;
                if (!is_array($value)) {
                    $value = (array)$value;
                }
                return new $cls($value);
            }
            if (!$this->validate($value)) {
                throw new Exception();
            }
            if (is_null($value)) {
                return null;
            }
            if ($this->hasFlag(Type::ARRAY_TYPE)) {
                return array_map(fn ($item) => $this->ref->castOut($item), $value);
            }
            if ($this->hasFlag(Type::OBJECT_TYPE)) {
                return (object) array_map(fn ($item, $key) => $this->ref[$key]->castOut($item), $value);
            }
            if ($this->hasFlag(Type::BOOLEAN_TYPE)) {
                return !!$value;
            }
            if ($this->hasFlag(Type::DATE_TYPE) || $this->hasFlag(Type::DATETIME_TYPE)) {
                return Carbon::parse($value);
            }
            if ($this->hasFlag(Type::ENUM_TYPE)) {
                return Utils::as_enum_or_null($this->ref, $value);
            }
            return $value;
        } catch (\Throwable $th) {
            throw new InvalidResponseTypeException($this, $value);
        }
    }
    public function validate($value)
    {
        if ($this->type === Type::NULL_TYPE && !is_null($value)) {
            return false;
        }

        if (is_null($value) && $this->hasFlag(Type::REQUIRED_TYPE)) {
            return false;
        }
        if (is_null($value)) {
            return true;
        }
        if ($this->hasFlag(Type::DYNAMIC_TYPE)) {
            return true;
        }

        if ($this->hasFlag(Type::ARRAY_TYPE)) {
            if (!is_array($value)) {
                return false;
            }
            $i = 0;
            foreach ($value as $key => $item) {
                if ($key !== $i) {
                    return false;
                }
                if (!$this->ref->validate($item)) {
                    return false;
                }
                $i++;
            }
        } elseif ($this->hasFlag(Type::OBJECT_TYPE)) {
            if (is_object($value)) {
                try {
                    $value = (array) $value;
                } catch (\Throwable $th) {
                }
            }
            if (!is_array($value)) {
                return false;
            }
            foreach ($value as $key => $item) {
                if (!isset($this->ref[$key])) {
                    return false;
                }
            }
            foreach ($this->ref as $key => $type) {
                if (!$type->validate($value[$key])) {
                    return false;
                }
            }
        } else {
            if ($this->hasFlag(Type::ID_TYPE)) {
                return is_numeric($value) && (is_int($value) || (is_string($value) && strval(intval($value)) === $value));
            }
            if ($this->hasFlag(Type::NUMBER_TYPE)) {
                return is_numeric($value);
            }
            if ($this->hasFlag(Type::INTEGER_TYPE)) {
                return is_int($value);
            }
            if ($this->hasFlag(Type::FLOAT_TYPE)) {
                return is_float($value);
            }
            if ($this->hasFlag(Type::STRING_TYPE)) {
                return is_string($value);
            }
            if ($this->hasFlag(Type::BOOLEAN_TYPE)) {
                return is_bool($value);
            }
            if ($this->hasFlag(Type::DATE_TYPE) || $this->hasFlag(Type::DATETIME_TYPE)) {
                try {
                    if (!($value instanceof Carbon) && !Carbon::parse($value)) {
                        throw new \Exception();
                    }
                } catch (\Throwable $th) {
                    return false;
                }
            }
            if ($this->hasFlag(Type::ENUM_TYPE)) {
                return Utils::as_enum_or_null($this->ref, $value) !== null;
            }
            if ($this->hasFlag(Type::MODEL_TYPE)) {
                return Utils::is_model_instance($value, $this->ref);
            }
        }
        return true;
    }
    public function expect()
    {
        if ($this->type === Type::NULL_TYPE) {
            return 'NULL';
        }
        if ($this->hasFlag(Type::DYNAMIC_TYPE)) {
            return 'anything';
        }

        $nullable = true;
        if ($this->hasFlag(Type::REQUIRED_TYPE)) {
            $nullable = false;
        }

        if ($this->hasFlag(Type::ARRAY_TYPE)) {
            return "array<" . $this->ref->expect() . ">" . ($nullable ? '|NULL' : '');
        }
        $type = "";
        if ($this->hasFlag(Type::ID_TYPE)) {
            $type = "ID";
        }
        if ($this->hasFlag(Type::NUMBER_TYPE)) {
            $type = "numeric";
        }
        if ($this->hasFlag(Type::INTEGER_TYPE)) {
            $type = "integer";
        }
        if ($this->hasFlag(Type::FLOAT_TYPE)) {
            $type = "float";
        }
        if ($this->hasFlag(Type::STRING_TYPE)) {
            $type = "string";
        }
        if ($this->hasFlag(Type::BOOLEAN_TYPE)) {
            $type = "true|false";
        }
        if ($this->hasFlag(Type::DATE_TYPE)) {
            $type = "date-string|Carbon";
        }
        if ($this->hasFlag(Type::ENUM_TYPE)) {
            $type = $this->ref;
        }
        if ($this->hasFlag(Type::MODEL_TYPE)) {
            $type = $this->ref;
        }
        return $type . ($nullable ? '|NULL' : '');
    }
}
