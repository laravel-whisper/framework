<?php

namespace LaravelWhisper\Whisper\Test;

use LaravelWhisper\Whisper\Test\Stubs\ModelStub;

class ExampleTest extends TestCase
{
  public function testMagicMethods()
  {
      $model = new ModelStub;
      $model->name = 'foo';
      $this->assertEquals('foo', $model->name);
      $this->assertTrue(isset($model->name));
      unset($model->name);
      $this->assertFalse(isset($model->name));
  }

  public function testArrayAccess()
  {
      $model = new ModelStub;
      $model['name'] = 'foo';
      $this->assertEquals('foo', $model['name']);
      $this->assertTrue(isset($model['name']));
      unset($model['name']);
      $this->assertFalse(isset($model['name']));
  }

  public function testToString()
  {
    $model = new ModelStub();

    $model->name = 'foo';

    $this->assertEquals(json_encode(['name' => 'foo']), (string) $model);
  }
}
