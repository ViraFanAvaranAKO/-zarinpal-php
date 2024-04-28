<?php

namespace Ako\Zarinpal\Php\Helpers;

use Ako\Zarinpal\Php\Exceptions\InvalidSelectionException;

class GraphQLQuery
{
    /**
     * Stores the GraphQL query format
     *
     * First string is object name
     * Second string is arguments
     * Third string is selection set
     *
     * @var string
     */
    protected const QUERY_FORMAT = "%s%s%s";

    /**
     * Stores the name of the type of the operation to be executed on the GraphQL server
     *
     * @var string
     */
    protected const OPERATION_TYPE = 'query';

    /**
     * Stores the object being queried for
     *
     * @var string
     */
    protected $fieldName;

    /**
     * Stores the list of arguments used when querying data
     *
     * @var array
     */
    protected $arguments;

    /**
     * Private member that's not accessible from outside the class, used internally to deduce if query is nested or not
     *
     * @var int
     */
    protected int $nestingLevel;

    /**
     * GQLQueryBuilder constructor.
     *
     * @param string $fieldName if no value is provided for the field name an empty query object is assumed
     */
    public function __construct(string $fieldName = '')
    {
        $this->fieldName     = $fieldName;
        $this->arguments     = [];
        $this->selectionSet  = [];
        $this->nestingLevel      = 0;
    }

    /**
     * Throwing exception when setting the arguments if they are incorrect because we can't throw an exception during
     * the execution of __ToString(), it's a fatal error in PHP
     *
     * @param array $arguments
     *
     * @return GraphQLQuery
     * @throws \InvalidArgumentException
     */
    public function setArguments(array $arguments): GraphQLQuery
    {
        // If one of the arguments does not have a name provided, throw an exception
        $nonStringArgs = array_filter(array_keys($arguments), function ($element) {
            return !is_string($element);
        });
        if (!empty($nonStringArgs)) {
            throw new \InvalidArgumentException(
                'One or more of the arguments provided for creating the query does not have a key, which represents argument name'
            );
        }
        $nonTupleArgs = array_filter(array_values($arguments), function ($element) {
            return !is_array($element) || count($element) !== 2 || !$element[0] instanceof Type;
        });
        if (!empty($nonTupleArgs)) {
            throw new \InvalidArgumentException(
                'One or more of the arguments provided for creating the query is not in the required tuple format of type-value'
            );
        }
        $nonValidValuedArgs = array_filter(array_values($arguments), function ($element) {
            return !$element[0]->validate($element[1]);
        });
        if (!empty($nonValidValuedArgs)) {
            throw new \InvalidArgumentException(
                'One or more of the arguments provided for creating the query is invalid based on its type definition'
            );
        }

        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return string
     */
    protected function constructArguments(): string
    {
        // Return empty string if list is empty
        if (empty($this->arguments)) {
            return '';
        }

        // Construct arguments string if list not empty
        $constraintsString = '(';
        $first             = true;
        foreach ($this->arguments as $name => $tuple) {

            // Append space at the beginning if it's not the first item on the list
            if ($first) {
                $first = false;
            } else {
                $constraintsString .= ' ';
            }
            list($type, $value) = $tuple;
            $constraintsString .= $name . ': ' . $type->castIn($value);
        }
        $constraintsString .= ')';

        return $constraintsString;
    }

    /**
     * Stores the selection set desired to get from the query, can include nested queries
     *
     * @var array
     */
    protected $selectionSet;

    /**
     * @param array $selectionSet
     *
     * @return $this
     * @throws InvalidSelectionException
     */
    public function setSelectionSet(array $selectionSet)
    {
        $nonStringsFields = array_filter($selectionSet, function ($element) {
            return !is_string($element) && !$element instanceof GraphQLQuery;
        });
        if (!empty($nonStringsFields)) {
            throw new InvalidSelectionException(
                'One or more of the selection fields provided is not of type string or Query'
            );
        }

        $this->selectionSet = $selectionSet;

        return $this;
    }

    /**
     * @return string
     */
    protected function constructSelectionSet(int $spacing = 0): string
    {
        if (empty($this->selectionSet)) {
            return '';
        }

        $attributesString = " {" . PHP_EOL . $this->getNestingLevelSpacing(1 + $spacing);
        $first            = true;
        foreach ($this->selectionSet as $attribute) {

            // Append empty line at the beginning if it's not the first item on the list
            if ($first) {
                $first = false;
            } else {
                $attributesString .= PHP_EOL . $this->getNestingLevelSpacing(1 + $spacing);
            }

            // If query is included in attributes set as a nested query
            if ($attribute instanceof GraphQLQuery) {
                $attribute->setNestLevel($this->nestingLevel + 1 + $spacing);
            }

            // Append attribute to returned attributes list
            $attributesString .= $attribute;
        }
        $attributesString .= PHP_EOL . $this->getNestingLevelSpacing(0 + $spacing) . "}";

        return $attributesString;
    }

    public function getSelectionSet()
    {
        return $this->selectionSet;
    }

    protected function getNestingLevelSpacing(int $dif = 0)
    {
        return str_repeat("  ", $this->nestingLevel + $dif);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $queryFormat = static::QUERY_FORMAT;
        $spacing = 0;
        if ($this->nestingLevel === 0) {
            if ($this->fieldName === '') {
                return  static::OPERATION_TYPE . $this->constructSelectionSet(0);
            } else {
                $spacing = 1;
                $queryFormat =  static::OPERATION_TYPE . " {" . PHP_EOL . $this->getNestingLevelSpacing(1) . static::QUERY_FORMAT . PHP_EOL . "}";
            }
        }
        $argumentsString = $this->constructArguments();

        return sprintf($queryFormat, $this->fieldName, $argumentsString, $this->constructSelectionSet($spacing));
    }

    /**
     *
     */
    protected function setNestLevel(int $level)
    {
        $this->nestingLevel = $level;
    }
}
