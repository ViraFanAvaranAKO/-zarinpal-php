<?php

namespace Ako\Zarinpal\Php\Helpers;

use Carbon\Carbon;

class QueryBuilder
{
    protected string $model;
    protected ?array $select = null;
    protected ?array $insert = null;
    protected ?array $update = null;
    protected ?array $delete = null;

    protected array $fields;

    public array $selection = [];
    public array $filters = [];


    public function __construct(string $model, object $settings)
    {

        if (!Utils::is_model($model)) {
            throw new \InvalidArgumentException("Provided model ($model) is not a Zarinpal GraphQL model.");
        }
        $this->model = $model;
        // $this->client = new Client(
        //     $settings->api_url,
        //     ['Authorization' => "Bearer {$settings->access_token}"],
        //     array('curl' => array(CURLOPT_SSL_VERIFYPEER => false,),)
        // );
        $this->fields = $this->model::getFieldDefinitions();
        $this->select = $this->model::getSelectQuery();
        $this->insert = $this->model::getInsertQuery();
        $this->update = $this->model::getUpdateQuery();
        $this->delete = $this->model::getDeleteQuery();
        $this->select_all_of($this->fields);
        if ($this->select == null && $this->insert == null && $this->update == null && $this->delete == null) {
            throw new \InvalidArgumentException("Provided model ($model) is not directly queryable.");
        }
    }

    private function select_all_of(array $fields, string $relation_prefix = "", $return = false)
    {
        $res = [];
        foreach ($fields as $field_name => $field_type) {
            if (Utils::is_model($field_type)) {
                $sub = $this->select_all_of($field_type::getFieldDefinitions(), $relation_prefix === "" ? $field_name : $relation_prefix . '.' . $field_name, true);
                array_push($res, ...$sub);
                continue;
            }
            $res[] =  $relation_prefix === "" ? $field_name : $relation_prefix . '.' . $field_name;
        }
        if ($return) {
            return $res;
        } else {
            $this->selection = $res;
        }
    }

    public function select(...$fields)
    {
        if ($this->select == null) {
            throw new \LogicException("Provided model ($this->model) is write only.");
        }
        $result = [];
        foreach ($fields as $field) {
            if (in_array($field, $this->selection)) {
                $result[] = $field;
            }
        }
        $this->selection = $result;

        return $this;
    }

    private function check_argument_type(array $schema, string $argument, $value)
    {
        try {
            $type = $schema[$argument];
        } catch (\Throwable $th) {
            throw new \InvalidArgumentException("Provided filter/column ($argument) is not available within $this->model.");
        }
        switch ($type) {
            case 'ID':
            case 'integer':
                if (!is_numeric($value) || intval($value) != $value) {
                    throw new \InvalidArgumentException("Provided value ($value) for '$argument' must be of type $type");
                }
                return intval($value);
            case 'number':
                if (!is_numeric($value)) {
                    throw new \InvalidArgumentException("Provided value ($value) for '$argument' must be of type $type");
                }
                return floatval($value);
            case 'str':
            case 'string':
                if (is_null($value) || !is_string($value) || $value == "") {
                    throw new \InvalidArgumentException("Provided value ($value) for '$argument' must be of type $type");
                }
                return $value;
            case 'bool':
            case 'boolean':
                if (!is_bool($value)) {
                    throw new \InvalidArgumentException("Provided value ($value) for '$argument' must be of type $type");
                }
                return $value;
            case 'date':
            case 'datetime':
                try {
                    if (!($value instanceof Carbon) && !Carbon::parse($value)) {
                        throw new \Exception();
                    }
                } catch (\Throwable $th) {
                    throw new \InvalidArgumentException("Provided value ($value) for '$argument' must be of type $type");
                }
                $value = $value instanceof Carbon ? $value : Carbon::parse($value);
                if ($type == "date") {
                    return $value->format('Y-m-d');
                } else {
                    return $value->format('Y-m-d H:i:s');
                }

            default:
                $is_enum = false;
                try {
                    $is_enum = (new \ReflectionClass($type))->isEnum();
                } catch (\Throwable $th) {
                }
                if ($is_enum) {
                    $class = "";
                    try {
                        $class = get_class($value);
                    } catch (\Throwable $th) {
                        try {
                            $value = $type::from($value);
                            $class = get_class($value);
                        } catch (\Throwable $th) {
                        }
                    }
                    if ($class != $type) {
                        throw new \InvalidArgumentException("Provided value ($value) for '$argument' must be of type $type");
                    }
                    return new RawObject($value->value);
                } else {
                    throw new \LogicException("Unsupported argument type ($type)");
                }
        }
    }

    public function where(string $argument, $value)
    {
        if ($this->select == null) {
            throw new \LogicException("Provided model ($this->model) is write only.");
        }
        $this->filters[$argument] = $this->check_argument_type($this->select['arguments'], $argument, $value);
        return $this;
    }

    private function cast_selection(array $selection)
    {
        $result = [];
        $subs = [];
        foreach ($selection as $item) {
            if (str_contains($item, ".")) {
                list($parent, $sub) = explode(".", $item);
                $subs[$parent] ??= [];
                $subs[$parent][] = $sub;
            } else {
                $result[] = $item;
            }
        }
        foreach ($subs as $parent => $items) {
            $result[] = (new Query($parent))
                ->setSelectionSet($this->cast_selection($items));
        }
        return $result;
    }

    private function generate_select_query(array $args = [])
    {
        if ($this->select == null) {
            throw new \LogicException("Provided model ($this->model) is write only.");
        }
        if (!count($this->selection)) {
            throw new \LogicException("Nothing selected.");
        }

        $q = (new Query($this->select['query']))
            ->setSelectionSet($this->cast_selection($this->selection));

        if (count($this->filters)) {
            $args = $this->filters;
        }
        if (count($this->select['defaults'])) {
            foreach ($this->select['defaults'] as $key => $value) {
                if (!isset($args[$key])) {
                    $args[$key] = $value;
                }
            }
        }
        $vars = [];
        if (count($this->select['required'])) {
            foreach ($this->select['required'] as $key) {
                if (!isset($args[$key])) {
                    $vars[] = new Variable('name', $this->select['arguments'][$key], true);
                }
            }
        }
        if (count($args)) {
            $q->setArguments($args);
        }
        if (count($vars)) {
            $q->setVariables($vars);
        }

        return $q;
    }
    private function generate_insert_query($args)
    {
        if ($this->insert == null) {
            if ($this->update == null && $this->delete == null) {
                throw new \LogicException("Provided model ($this->model) is read only.");
            } else {
                throw new \LogicException("Provided model ($this->model) does not provide any insertion api.");
            }
        }

        if (!count($this->selection)) {
            throw new \LogicException("Nothing selected.");
        }

        $q = (new Mutation($this->insert['mutation']))
            ->setSelectionSet($this->cast_selection($this->selection));

        if (count($this->insert['defaults'])) {
            foreach ($this->insert['defaults'] as $key => $value) {
                if (!isset($args[$key])) {
                    $args[$key] = $value;
                }
            }
        }
        if (count($this->insert['required'])) {
            foreach ($this->insert['required'] as $key) {
                if (!isset($args[$key])) {
                    throw new \InvalidArgumentException("Provided arguments does not meet requirements. Property '$key' is required.");
                }
            }
        }
        $final_args = [];
        foreach ($args as $argument => $value) {
            $final_args[$argument] = $this->check_argument_type($this->insert['arguments'], $argument, $value);
        }

        if (count($final_args)) {
            $q->setArguments($final_args);
        }

        return $q;
    }
    private function generate_update_query($args)
    {
        if ($this->update == null) {
            if ($this->insert == null && $this->delete == null) {
                throw new \LogicException("Provided model ($this->model) is read only.");
            } else {
                throw new \LogicException("Provided model ($this->model) does not provide any update api.");
            }
        }

        if (!count($this->selection)) {
            throw new \LogicException("Nothing selected.");
        }

        $q = (new Mutation($this->update['mutation']))
            ->setSelectionSet($this->cast_selection($this->selection));

        if (count($this->update['defaults'])) {
            foreach ($this->update['defaults'] as $key => $value) {
                if (!isset($args[$key])) {
                    $args[$key] = $value;
                }
            }
        }
        if (count($this->update['required'])) {
            foreach ($this->update['required'] as $key) {
                if (!isset($args[$key])) {
                    throw new \InvalidArgumentException("Provided arguments does not meet requirements. Property '$key' is required.");
                }
            }
        }
        $final_args = [];
        foreach ($args as $argument => $value) {
            $final_args[$argument] = $this->check_argument_type($this->update['arguments'], $argument, $value);
        }

        if (count($final_args)) {
            $q->setArguments($final_args);
        }

        return $q;
    }

    private function call($query, array $variables = [])
    {
        try {
            $results = $this->client->runQuery($query, true, $variables);
            return $results;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function get(array $additional_args = [])
    {
        $query = $this->generate_select_query();
        $result = $this->call($query, $additional_args);
        return $result->getData()[$this->select['query']];
    }

    public function first(array $additional_args = [])
    {
        $query = $this->generate_select_query([
            'limit' => 1,
            'offset' => 0
        ]);

        $result = $this->call($query, $additional_args);
        return isset($result->getData()[$this->select['query']][0]) ? $result->getData()[$this->select['query']][0] : null;
    }

    public function insert(array $data)
    {
        $query = $this->generate_insert_query($data);

        $result = $this->call($query, []);
        return $result->getData();
    }

    public function update(array $data)
    {
        $query = $this->generate_update_query($data);

        $result = $this->call($query, []);
        return $result->getData();
    }
}
