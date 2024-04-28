<?php

namespace Ako\Zarinpal\Php\Drivers;

use Ako\Zarinpal\Php\Abstracts\Core;
use Ako\Zarinpal\Php\Contracts\IZarinpalQueryable;
use Ako\Zarinpal\Php\Exceptions\InvalidArgumentValueException;
use Ako\Zarinpal\Php\Exceptions\InvalidMethodArgumentsException;
use Ako\Zarinpal\Php\Helpers\GraphQLQuery;
use Ako\Zarinpal\Php\Helpers\HttpClient;
use Ako\Zarinpal\Php\Helpers\Type;
use Ako\Zarinpal\Php\Helpers\Utils;
use Ako\Zarinpal\Php\Traits\HasQueries;
use Ako\Zarinpal\Php\Traits\IsSelectable;
use BadMethodCallException;

class QueryBuilderDriver implements IZarinpalQueryable
{
    protected Core $_core;
    protected HttpClient $client;
    protected string $model;
    protected bool $isSelectable;
    protected bool $hasQueries;
    protected array $queries = [];
    protected ?array $select_schema = null;
    protected array $fields;
    protected array $relations;

    public array $selection = [];
    protected array $filters = [];

    public function __construct(Core $core, string $model)
    {
        $this->_core = $core;
        if (!Utils::is_model($model)) {
            throw new \InvalidArgumentException("Provided model ($model) is not a Zarinpal GraphQL model.");
        }
        $this->model = $model;
        $this->isSelectable = in_array(IsSelectable::class, class_uses($model));
        $this->hasQueries = in_array(HasQueries::class, class_uses($model)) && count($model::getExtraQueries()) > 0;
        if (!$this->isSelectable && !$this->hasQueries) {
            throw new \InvalidArgumentException("Provided model ($model) is not queryable.");
        }
        $this->fields = $model::getFields();
        $this->relations = $model::getRelations();
        if ($this->isSelectable) {
            $this->select_schema = [
                'operation' => $model::getSelectOperation(),
                'arguments' => $model::getSelectArguments()
            ];
            $this->selection = Utils::get_full_default_selection($model);
        }
        if ($this->hasQueries) {
            $this->queries = $model::getExtraQueries();
        }
        $this->client = new HttpClient($this->_core);
    }

    private function call($query, $additional_args = [])
    {
        return $this->client->runQuery($query);
    }

    public function with(...$relations)
    {
        $result = $this->selection;
        foreach ($relations as $relation) {
            $path = explode('.', $relation);
            $relations = $this->relations;
            $current_model = $this->model;
            $current_prefix = "";
            do {
                $item = array_shift($path);
                if ($item) {
                    if (!isset($relations[$item])) {
                        throw new \InvalidArgumentException("The model ({$current_model}) does not have a relation named '{$item}'.");
                    }
                    $current_model = $relations[$item]->model;
                    $relations = $current_model::getRelations();
                    $current_prefix = $current_prefix == "" ? $item : $current_prefix . "." . $item;
                } else {
                    array_push($result, ...Utils::get_full_default_selection($current_model, $current_prefix));
                }
            } while ($item != null);
        }
        $this->selection = array_unique($result);

        return $this;
    }

    public function select(...$fields)
    {
        if (!$this->isSelectable) {
            throw new \LogicException("Provided model ($this->model) is not selectable.");
        }
        $result = [];
        foreach ($fields as $field) {
            $path = explode('.', $field);
            $relations = $this->relations;
            $current_model = $this->model;
            $i = 0;
            $t = count($path);
            do {
                $item = array_shift($path);
                if ($item) {
                    if ($i == $t - 1) {
                        if (!isset($current_model::getFields()[$item])) {
                            throw new \InvalidArgumentException("The model ({$current_model}) does not have a column named '{$item}'.");
                        }
                        $result[] = $field;
                    } else {
                        if (!isset($relations[$item])) {
                            throw new \InvalidArgumentException("The model ({$current_model}) does not have a relation named '{$item}'.");
                        }
                        $current_model = $relations[$item]->model;
                        $relations = $current_model::getRelations();
                    }
                    $i++;
                }
            } while ($item != null);
        }
        $this->selection = array_unique($result);

        return $this;
    }

    public function where(string $argument, $value)
    {
        if (!$this->isSelectable) {
            throw new \LogicException("Provided model ($this->model) is not selectable.");
        }
        if (!isset($this->select_schema['arguments'][$argument])) {
            throw new \InvalidArgumentException("Filter argument ({$argument}) could not be found in provided model.");
        }
        if (!$this->select_schema['arguments'][$argument]->validate($value)) {
            throw new InvalidArgumentValueException($argument, $this->select_schema['arguments'][$argument], $value);
        }

        $this->filters[$argument] = $value;
        return $this;
    }

    private function cast_selection(array $selection)
    {
        $result = [];
        $subs = [];
        foreach ($selection as $item) {
            if (str_contains($item, ".")) {
                $all = explode(".", $item);
                $first = array_shift($all);
                $rest = join('.', $all);
                if (!isset($subs[$first])) {
                    $subs[$first] = [];
                }
                $subs[$first][] = $rest;
            } else {
                $result[] = $item;
            }
        }
        foreach ($subs as $parent => $items) {
            $result[] = (new GraphQLQuery($parent))
                ->setSelectionSet($this->cast_selection($items));
        }
        return $result;
    }

    protected function generate_select_query($additional_args = [])
    {
        if (!$this->isSelectable) {
            throw new \LogicException("Provided model ($this->model) is not selectable.");
        }
        if (!count($this->selection)) {
            throw new \LogicException("Nothing selected.");
        }

        $q = (new GraphQLQuery($this->select_schema['operation']))
            ->setSelectionSet($this->cast_selection($this->selection));

        $args = [];
        if (count($this->filters)) {
            $args = $this->filters;
        }
        foreach ($additional_args as $key => $value) {
            $args[$key] = [Type::dynamic(), $value];
        }
        // if (count($this->select['defaults'])) {
        //     foreach ($this->select['defaults'] as $key => $value) {
        //         if (!isset($args[$key])) {
        //             $args[$key] = $value;
        //         }
        //     }
        // }
        // $vars = [];
        // if (count($this->select['required'])) {
        //     foreach ($this->select['required'] as $key) {
        //         if (!isset($args[$key])) {
        //             $vars[] = new Variable('name', $this->select['arguments'][$key], true);
        //         }
        //     }
        // }
        if (count($args)) {
            $q->setArguments($args);
        }
        // if (count($vars)) {
        //     $q->setVariables($vars);
        // }

        return $q;
    }

    protected function generate_extra_query($schema, $data)
    {
        $cls = $schema['type'];
        $q = (new $cls($schema['operation']));

        $required_args = [];
        foreach ($schema['arguments'] as $key => $value) {
            if ($value->hasFlag(Type::REQUIRED_TYPE)) {
                $required_args[] = $key;
            }
        }

        $invalid_arg_count = (!count($schema['arguments']) && count($data)) || (count($required_args) && count($data) != 1) || (!count($required_args) && count($data) > 1);
        if ($invalid_arg_count) {
            throw new InvalidMethodArgumentsException($schema['as'], array_keys($schema['arguments']), $required_args);
        }
        $args = [];
        if (count($data) == 1) {
            $args = $data[0];
        }
        foreach ($required_args as $required_arg) {
            if (!isset($args[$required_arg])) {
                throw new InvalidMethodArgumentsException($schema['as'], array_keys($schema['arguments']), $required_args);
            }
        }

        foreach ($schema['arguments'] as $arg => $type) {
            if (array_key_exists($arg, $args)) {
                if (!$type->validate($args[$arg])) {
                    throw new InvalidArgumentValueException($arg, $type, $args[$arg]);
                }
                $args[$arg] = [$type, $args[$arg]];
            }
        }

        $selection = $schema['return_type']->toSelectionArray();
        if (count($selection)) {
            $q->setSelectionSet($this->cast_selection($selection));
        }

        if (count($args)) {
            $q->setArguments($args);
        }
        return $q;
    }

    public function get(array $additional_args = [])
    {
        $query = $this->generate_select_query();
        $result = $this->call($query, $additional_args);
        return $result->cast($this->select_schema['operation'], Type::list(Type::model($this->model)));
    }

    public function first(array $additional_args = [])
    {
        $query = $this->generate_select_query([
            'limit' => 1,
            'offset' => 0
        ]);

        $result = $this->call($query, $additional_args);
        return isset($result->getData()[$this->select_schema['operation']][0])
            ? Type::model($this->model, true)->castOut($result->getData()[$this->select_schema['operation']][0]) : null;
    }

    public function __call($method, $args)
    {
        $availableMethods = array_column($this->queries, 'as');
        if (!in_array($method, $availableMethods)) {
            throw new BadMethodCallException("The method ({$method}) could not be found in provided model ({$this->model}). Available methods are: get, first, with, select, where" . (count($availableMethods) ? ", " : "") . implode(", ", $availableMethods) . ".");
        }
        $index = array_search($method, $availableMethods);
        $query_schema = $this->queries[$index];
        $query = $this->generate_extra_query($query_schema, $args);
        $result = $this->call($query);
        return $result->cast($query_schema['operation'], $query_schema['return_type']);
    }
}
