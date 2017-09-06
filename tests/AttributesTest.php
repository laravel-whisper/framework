<?php

namespace LaravelWhisper\Whisper\Test;

use LaravelWhisper\Whisper\Test\Stubs\ModelStub;

class AttributesTest extends TestCase
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

    public function testArrayAccessToAttributes()
    {
        $model = new ModelStub(['foo' => 1, 'bar' => 2, 'baz' => 3]);
        unset($model['baz']);
        $this->assertTrue(isset($model['foo']));
        $this->assertEquals($model['foo'], 1);
        $this->assertTrue(isset($model['bar']));
        $this->assertEquals($model['bar'], 2);
        $this->assertFalse(isset($model['baz']));
        $this->assertNull($model['baz']);
        $this->assertFalse(isset($model['eggs']));
    }

    public function testConstructor()
    {
        $model = new ModelStub(['foo' => 'bar']);

        $this->assertEquals('bar', $model->getAttribute('foo'));
    }

    public function testToString()
    {
        $model = new ModelStub(['name' => 'foo']);

        $this->assertEquals(json_encode(['name' => 'foo']), (string) $model);
    }

    public function testGetter()
    {
        $model = new class extends ModelStub {
            public function getGetterAttribute()
            {
                return 'getter';
            }
        };

        $this->assertEquals('getter', $model->getter);
    }

    public function testSetter()
    {
        $model = new class extends ModelStub {
            public function setSetterAttribute($value)
            {
                $this->setter = 'setter';
            }
        };
        $model->setter = 'getter';

        $this->assertEquals('setter', $model->setter);
    }

    public function testDirtyAttributes()
    {
        $model = new ModelStub(['foo' => '1', 'bar' => 2, 'baz' => 3]);
        $model->syncOriginal();
        $model->foo = 1;
        $model->bar = 20;
        $model->baz = 30;
        $this->assertTrue($model->isDirty());
        $this->assertFalse($model->isDirty('foo'));
        $this->assertTrue($model->isDirty('bar'));
        $this->assertTrue($model->isDirty('foo', 'bar'));
        $this->assertTrue($model->isDirty(['foo', 'bar']));
    }

    public function testCleanAttributes()
    {
        $model = new ModelStub(['foo' => '1', 'bar' => 2, 'baz' => 3]);
        $model->syncOriginal();
        $model->foo = 1;
        $model->bar = 20;
        $model->baz = 30;
        $this->assertFalse($model->isClean());
        $this->assertTrue($model->isClean('foo'));
        $this->assertFalse($model->isClean('bar'));
        $this->assertFalse($model->isClean('foo', 'bar'));
        $this->assertFalse($model->isClean(['foo', 'bar']));
    }

    public function testCalculatedAttributes()
    {
        $model = new class extends ModelStub {
          public function getPasswordAttribute()
          {
            return '******';
          }

          public function setPasswordAttribute($value)
          {
            $this->attributes['password_hash'] = sha1($value);
          }
        };
        $model->password = 'secret';
        $attributes = $model->getAttributes();
        // ensure password attribute was not set to null
        $this->assertArrayNotHasKey('password', $attributes);
        $this->assertEquals('******', $model->password);
        $hash = 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4';
        $this->assertEquals($hash, $attributes['password_hash']);
        $this->assertEquals($hash, $model->password_hash);
    }

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
