<?php

namespace App\Models\Redis;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

/**
 * Hash based Redis storage
 */
abstract class AbstractModel
{
    /**
     * Prefix of hash
     *
     * @var string
     */
    protected $prefix;

    protected $primaryKey;

    /**
     * Hash's attributes
     *
     * @var array
     */
    protected $attributes;

    static public function __callStatic(string $method, array $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    public function __call(string $name, array $arguments)
    {
        $method_map = [
            'find' => 'findByKey'
        ];

        $actual_method = $method_map[$name];

        return $this->$actual_method(...$arguments);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    abstract protected function getNextIncrementingKey();

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function fill(array $attributes)
    {
        $this->setAttributes($attributes);
    }

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

    public function save()
    {
        $key = $this->prefix . ':' . $this->getNextIncrementingKey();

        $status = Redis::hmset($key, $this->attributes);

        return $status;
    }

    public function delete()
    {
        Redis::del();
    }

    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }
}