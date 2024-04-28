<?php

namespace Ako\Zarinpal\Php\Helpers;

class Relation
{
    public static function hasOne(string $model)
    {
        return new Relation($model, false);
    }
    public static function hasMany(string $model)
    {
        return new Relation($model, true);
    }

    public string $model;
    protected bool $many;
    public function __construct(string $model, $many)
    {
        $this->many = $many;
        $this->model = $model;
    }
    public function instantiate($data)
    {
        if (!is_array($data)) {
            $data = (array)$data;
        }
        if ($this->many) {
            $result = [];
            foreach ($data as $values) {
                $result[] = $this->model::instantiate((array)$values);
            }
            return $result;
        } else {
            return $this->model::instantiate($data);
        }
    }
}
