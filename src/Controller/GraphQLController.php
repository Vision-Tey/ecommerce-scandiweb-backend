<?php

namespace App\Controller;

use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Throwable;
use App\Schema\QueryType;
use App\Schema\MutationType;

class GraphQLController
{
    public function handle()
    {
        try {
            // Initialize the Query and Mutation types
            $queryType = new QueryType();
            $mutationType = new MutationType();

              // Create the GraphQL schema
              $schema = new Schema([
                'query' => $queryType,
                'mutation' => $mutationType,
            ]);

            // Get and decode the input
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }

            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variables = isset($input['variables']) ? $input['variables'] : null;
            
            $rootValue = [];
               // Execute the query
               $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variables);
               $output = $result->toArray();
               echo json_encode($output);
        } catch (Throwable $e) {
            // Handle errors
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
            echo json_encode($output);
        }
    }
}
