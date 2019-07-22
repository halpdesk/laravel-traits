<?php

namespace Halpdesk\LaravelTraits\Exceptions;

use Exception;
use Throwable;
use Halpdesk\LaravelTraits\Contracts\Exception as LaravelTraitsExceptionContract;

/**
 * @author Daniel Leppänen
 */
class FilterableTraitException extends Exception implements LaravelTraitsExceptionContract
{

    /**
     * @var String
     */
    private $operator;

    /**
     * @param null|String $message
     * @param String $operator
     * @return void
     */
    public function __construct($message = 'filter_operator_not_valid', String $operator, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->operator = $operator;
    }

    public function getOperator()
    {
        return $this->operator;
    }
}
