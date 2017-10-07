<?php

namespace App\Models\Redis;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

/**
 * Class RedisModel
 * Hash based Redis storage. Something like Yii's redis\ActiveRecord
 * @package App\Models
 */
abstract class AbstractModel
{
    /**
     * Prefix of hash
     *
     * @var string
     */
    protected $prefix;

    /**
     * Hash's attributes
     *
     * @var array
     */
    protected $attributes;

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @param $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get next incrementing key
     *
     * @return mixed
     */
    abstract protected function getNextIncrementingKey();

    /**
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public static function __callStatic(string $method, array $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    /**
     * @param $name
     * @param $arguments
     */
    public function __call(string $name, array $arguments)
    {
        $method_map = [
            'find' => 'findByKey'
        ];

        $actual_method = $method_map[$name];

        return $this->$actual_method(...$arguments);
    }

    /**
     * @param $key
     * @return AbstractModel
     */
    public function findByKey($key)
    {
        $data = Redis::hgetall($this->prefix . ':' . $key);

        if (empty($data)) {
            return null;
        }

        $instance = new static();
        $instance->setAttributes($data);
        return $instance;
    }

    /**
     * @return mixed
     */
    public function save()
    {
        $key = $this->prefix . ':' . $this->getNextIncrementingKey();

        $status = Redis::hmset($key, $this->attributes);

        return $status;
    }

    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }
}