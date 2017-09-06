<?php

namespace LaravelWhisper\Whisper\Test;

use LaravelWhisper\Whisper\Test\Stubs\ModelStub;

class HelpersTest extends TestCase
{
    public function testOnly()
    {
        $model = new ModelStub;
        $model->first_name = 'taylor';
        $model->last_name = 'otwell';
        $model->project = 'laravel';
        $this->assertEquals(['project' => 'laravel'], $model->only('project'));
        $this->assertEquals(['first_name' => 'taylor', 'last_name' => 'otwell'], $model->only('first_name', 'last_name'));
        $this->assertEquals(['first_name' => 'taylor', 'last_name' => 'otwell'], $model->only(['first_name', 'last_name']));
    }
}
