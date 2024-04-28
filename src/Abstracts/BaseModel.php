<?php

namespace Ako\Zarinpal\Php\Abstracts;

use Ako\Zarinpal\Php\Contracts\IZarinpalModel;
use Ako\Zarinpal\Php\Helpers\Relation;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Helpers\Utils;

abstract class BaseModel implements IZarinpalModel
{
    /**
     * @return array<string, Type>
     */
    public abstract static function getFields(): array;
    /**
     * @return array<string, Relation>
     */
    public abstract static function getRelations(): array;
    /**
     * @return string[]
     */
    public static function getEagerLoads(): array
    {
        return [];
    }

    /**
     * @var array<string, mixed>
     */
    protected array $attributes = [];

    /**
     * @var array<string, IZarinpalModel|array<IZarinpalModel>>
     */
    protected array $relation_instances = [];

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (isset(static::getFields()[$key])) {
                $this->setAttribute($key, $value);
            }
            if (isset(static::getRelations()[$key])) {
                $this->relation_instances[$key] = static::getRelations()[$key]->instantiate($value);
            }
        }
    }

    public static function instantiate($data)
    {
        if (Utils::is_model_instance($data, static::class)) {
            return $data;
        } elseif (is_array($data)) {
            return new static($data);
        } else {
            try {
                return new static((array) $data);
            } catch (\Throwable $th) {
                throw new \InvalidArgumentException("Can not instantiate using data of type " . gettype($data));
            }
        }
    }

    protected function getAttribute(string $key)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    protected function setAttribute(string $key, $value)
    {
        $type = static::getFields()[$key];
        $this->attributes[$key] = $type->castOut($value);
    }

    public function __get($name)
    {
        if (isset(static::getFields()[$name])) {
            return $this->getAttribute($name);
        }
        if (isset(static::getRelations()[$name])) {
            return isset($this->relation_instances[$name]) ? $this->relation_instances[$name] : null;
        }
    }
}
