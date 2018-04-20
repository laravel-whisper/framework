<?php

namespace LaravelWhisper\Whisper;

interface ClientInterface
{
    public static function find($identifier) : array;

    public static function all() : array;

    public static function update($identifier, array $data) : void;

    public static function delete($identifier) : ?bool;

    public static function create(array $data) : array;
}
