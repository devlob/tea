<?php

namespace Devlob\Database\Goku\Builder;

use Devlob\Database\Goku\Model;
use ReflectionException;

/**
 * Trait Events
 *
 * Model events.
 *
 * @package Devlob\Database\Goku\Builder
 */
trait Events
{
    /**
     * Run 'beforeInsert' event.
     *
     * @param Model $model
     *
     * @throws ReflectionException
     */
    private function beforeInsert(Model &$model): void
    {
        $observer = $this->getObserver($model);

        if (method_exists($observer, 'creating')) {
            call_user_func([$this->getObserver($model), 'creating'], $model);
        }
    }

    /**
     * Run 'afterInsert' event.
     *
     * @param Model $model
     *
     * @throws ReflectionException
     */
    private function afterInsert(Model &$model): void
    {
        $observer = $this->getObserver($model);

        if (method_exists($observer, 'created')) {
            call_user_func([$this->getObserver($model), 'created'], $model);
        }
    }

    /**
     * Run 'beforeUpdate' event.
     *
     * @param Model $model
     *
     * @throws ReflectionException
     */
    private function beforeUpdate(Model &$model): void
    {
        $observer = $this->getObserver($model);

        if (method_exists($observer, 'updating')) {
            call_user_func([$this->getObserver($model), 'updating'], $model);
        }
    }

    /**
     * Run 'afterUpdate' event.
     *
     * @param Model $model
     *
     * @throws ReflectionException
     */
    private function afterUpdate(Model &$model): void
    {
        $observer = $this->getObserver($model);

        if (method_exists($observer, 'updated')) {
            call_user_func([$this->getObserver($model), 'updated'], $model);
        }
    }

    /**
     * Run 'beforeDelete' event.
     *
     * @param Model $model
     *
     * @throws ReflectionException
     */
    private function beforeDelete(Model &$model): void
    {
        $observer = $this->getObserver($model);

        if (method_exists($observer, 'deleting')) {
            call_user_func([$this->getObserver($model), 'deleting'], $model);
        }
    }

    /**
     * Run 'afterDelete' event.
     *
     * @param Model $model
     *
     * @throws ReflectionException
     */
    private function afterDelete(Model &$model): void
    {
        $observer = $this->getObserver($model);

        if (method_exists($observer, 'deleted')) {
            call_user_func([$this->getObserver($model), 'deleted'], $model);
        }
    }

    /**
     * Get observer.
     *
     * @param Model $model
     *
     * @return null|mixed
     * @throws ReflectionException
     */
    private function getObserver(Model &$model)
    {
        $class = "App\\Http\Observers\\{$model->getReflectionClass()->getShortName()}Observer";

        if (class_exists($class)) {
            return new $class();
        }

        return null;
    }
}