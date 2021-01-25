<?php

namespace Devlob\Http\Resources;

use Devlob\Database\Goku\Model;
use Exception;

class JsonResource
{
    /**
     * Resource model.
     *
     * @var Model
     */
    private $model;

    /**
     * JsonResource constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Magic getter to get model attributes.
     *
     * @param string $name
     *
     * @return mixed
     * @throws Exception
     */
    public function __get(string $name)
    {
        $methodName = 'get' . str_replace('_', '', ucwords($name, '_')) . 'Attribute';

        if (method_exists($this->model, $methodName)) {
            return $this->model->$methodName();
        }

        if (property_exists($this->model, $name)) {
            return $this->model->$name;
        }

        throw new Exception("Undefined property. Check if '$name' exists in '{$this->model->getClass()}' model.");
    }

    /**
     * Magic getter to get model functions.
     *
     * @param string $name
     * @param        $arguments
     *
     * @return mixed
     * @throws Exception
     */
    public function __call(string $name, $arguments)
    {
        if (method_exists($this->model, $name)) {
            return $this->model->$name();
        }

        throw new Exception("Undefined method. Check if '$name' exists in the model.");
    }
}
