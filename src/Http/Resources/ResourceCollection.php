<?php

namespace Devlob\Http\Resources;

/**
 * Class ResourceCollection
 *
 * Represent a collection.
 *
 * @package Devlob\Http\Resources
 */
class ResourceCollection
{
    /**
     * The path to the resource that needs to be collected.
     *
     * @var string
     */
    protected $collects;

    /**
     * Store resources.
     *
     * @var array
     */
    private $collection = [];

    /**
     * ResourceCollection constructor.
     *
     * @param array $resources
     */
    public function __construct(array $resources)
    {
        foreach ($resources as $resource) {
            $this->collection[] = (new $this->collects($resource))->toArray();
        }
    }

    /**
     * Get collection.
     *
     * @return array
     */
    public function getCollection(): array
    {
        return $this->collection;
    }
}
