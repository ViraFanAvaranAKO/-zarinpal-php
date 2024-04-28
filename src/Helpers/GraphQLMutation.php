<?php

namespace Ako\Zarinpal\Php\Helpers;

/**
 * Class Mutation
 */
class GraphQLMutation extends GraphQLQuery
{
    /**
     * Stores the name of the type of the operation to be executed on the GraphQL server
     *
     * @var string
     */
    protected const OPERATION_TYPE = 'mutation';
}
