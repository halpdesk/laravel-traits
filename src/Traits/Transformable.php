<?php

namespace Halpdesk\LaravelTraits\Traits;

use Illuminate\Support\Str;
use League\Fractal\Manager;
use League\Fractal\Resource\Item as FractalItem;
use Halpdesk\LaravelTraits\Transformers\ArrayTransformer;
use Halpdesk\LaravelTraits\Serializers\StandardSerializer;

/**
 * @author Daniel Leppänen
 */
trait Transformable
{
    /**
     * @var String $transformer Class name of the transformer belong to the class
     */
    protected $transformer = ArrayTransformer::class;

    /**
     * @var Array $relationships      Array which holds loaded relationships
     */
    protected static $relationships = [];

    /**
     * Override to put the relations loaded into nested array
     *
     * @param String|Array  $includes  The relations to load
     * @return Illuminate\Database\Eloquent\Model
     */
    public static function with($includes)
    {
        static::$relationships[static::class] = isset(static::$relationships[static::class]) ? static::$relationships[static::class] : [];

        if (!is_array($includes)) {
            $includes = explode(',', str_replace(' ', '', $includes));
        }

        if ($includes ==! [] && array_keys($includes) !== range(0, count($includes) - 1)) {
            $includesWithQueries = $includes;
            $includes = array_keys($includes);
        }

        if (isset(static::$relationships[static::class]) && !in_array($includes, static::$relationships[static::class])) {
            static::$relationships[static::class] = array_merge(static::$relationships[static::class], $includes);
        }

        if (isset($includesWithQueries)) {
            $result = parent::with($includesWithQueries);
        } else {
            $result = parent::with($includes);
        }
        return $result;
    }

    /**
     * Override to put the relations loaded into nested array
     *
     * @param String|Array  $includes  The relations to load
     * @return Illuminate\Database\Eloquent\Model
     */
    public function load($includes)
    {
        static::$relationships[static::class] = isset(static::$relationships[static::class]) ? static::$relationships[static::class] : [];

        if (!is_array($includes)) {
            $includes = explode(',', str_replace(' ', '', $includes));
        }
        // if associative
        if ($includes ==! [] && array_keys($includes) !== range(0, count($includes) - 1)) {
            $includesWithQueries = $includes;
            $includes = array_keys($includes);
        }

        if (!in_array($includes, static::$relationships[static::class])) {
            static::$relationships[static::class] = array_merge(static::$relationships[static::class], $includes);
        }

        if (isset($includesWithQueries)) {
            $result = parent::load($includesWithQueries);
        } else {
            $result = parent::load($includes);
        }
        return $result;
    }

    /**
     * Add includes to transformer
     *
     * @param String|Array  $includes  The includes to be transformed
     * @return void
     */
    public function addIncludes($includes)
    {
        if (!is_array($includes)) {
            $includes = explode(',', str_replace(' ', '', $includes));
        }

        if (!in_array($includes, static::$relationships)) {
            static::$relationships[static::class] = array_merge(static::$relationships[static::class], $includes);
        }
    }

    /**
     * Get loaded relations if a model has loaded with any
     *
     * @return Array
     */
    public function getIncludes()
    {
        return static::$relationships[static::class] ?? [];
    }

    /**
     *
     * Get all the loaded relation table names
     *
     * @return Array
     *
     * @see Illuminate\Database\Eloquent\Model::getTable()
     */
    public function getRelationTableNames()
    {
        $tableNames = [];
        foreach (static::$relationships[static::class] as $relations) {
            $tableName = str_replace(
                '\\', '', Str::snake(Str::plural(class_basename($relations)))
            );
            if (!in_array($tableName, $tableNames)) {
                $tableNames[] = $tableName;
            }
        }
        return $tableNames;
    }

    /**
     *
     * Get all the loaded relation object names
     *
     * @return Array
     *
     * @see Illuminate\Database\Eloquent\Model::getTable()
     */
    public function getRelationObjectNames($namespace = __NAMESPACE__)
    {
        $objectNames = [];
        foreach (static::$relationships as $relations) {
            $objectName = $namespace.'\\'.ucfirst(Str::camel(Str::singular($relations)));
            if (!in_array($objectName, $objectNames)) {
                $objectNames[] = $objectName;
            }
        }
        return $objectNames;
    }

    /**
     * Get the transformer for a model. Returns null if not set
     *
     * @return string       The class name of the transformer
     */
    public function getTransformer()
    {
        $transformer = $this->transformer;
        return !empty($transformer) ? $transformer : null;
    }

    /**
     * Convert the model instance to an array through a fractal transformer class
     *
     * @return Array
     */
    public function transform() : array
    {
        $fractal = new Manager;
        $fractal->setSerializer(new StandardSerializer);
        $resource = new FractalItem($this, new $this->transformer);
        $fractal->parseIncludes($this->getIncludes());
        $data = $fractal->createData($resource)->toArray();
        return $data;
    }
}
