<?php

namespace LaravelWhisper\Whisper;

use ArrayAccess;
use JsonSerializable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Concerns\HidesAttributes;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class Model implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
  use HasAttributes, HasTimestamps, HasRelationships, HidesAttributes, HasEvents;

  /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected static $dispatcher;

  /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_at';
    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return $this->incrementing;
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge($this->attributesToArray(), $this->relationsToArray());
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param  int  $options
     * @return string
     *
     * @throws \Illuminate\Database\Eloquent\JsonEncodingException
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }
        return $json;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the model to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

  /**
 * Dynamically retrieve attributes on the model.
 *
 * @param  string  $key
 * @return mixed
 */
public function __get($key)
{
    return $this->getAttribute($key);
}
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
/**
 * Determine if the given attribute exists.
 *
 * @param  mixed  $offset
 * @return bool
 */
public function offsetExists($offset)
{
    return ! is_null($this->getAttribute($offset));
}
/**
 * Get the value for a given offset.
 *
 * @param  mixed  $offset
 * @return mixed
 */
public function offsetGet($offset)
{
    return $this->getAttribute($offset);
}
/**
 * Set the value for a given offset.
 *
 * @param  mixed  $offset
 * @param  mixed  $value
 * @return void
 */
public function offsetSet($offset, $value)
{
    $this->setAttribute($offset, $value);
}
/**
 * Unset the value for a given offset.
 *
 * @param  mixed  $offset
 * @return void
 */
public function offsetUnset($offset)
{
    unset($this->attributes[$offset], $this->relations[$offset]);
}
/**
 * Determine if an attribute or relation exists on the model.
 *
 * @param  string  $key
 * @return bool
 */
public function __isset($key)
{
    return $this->offsetExists($key);
}
/**
 * Unset an attribute on the model.
 *
 * @param  string  $key
 * @return void
 */
public function __unset($key)
{
    $this->offsetUnset($key);
}
}
