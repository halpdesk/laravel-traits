<?php

namespace Halpdesk\LaravelTraits\Exceptions;

use Exception;
use Throwable;
use Halpdesk\LaravelTraits\Contracts\Exception as LaravelTraitsExceptionContract;

/**
 * @author Daniel Leppänen
 */
class AttributeNotFoundException extends Exception implements LaravelTraitsExceptionContract
{
    /**
     * @var String
     */
    private $class;

    /**
     * @var String
     */
    private $attribute;

    /**
     * @var Array
     */
    private $arguments;

    /**
     * @param String $message
     * @param String $class
     * @param String $attribute
     * @param null|Array $arguments
     * @return void
     */
    public function __construct($message, String $class, String $attribute, $arguments = [], Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->class = $class;
        $this->attribute = $attribute;
        $this->arguments = $arguments;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    public function getArguments()
    {
        return $this->arguments;
    }
}
