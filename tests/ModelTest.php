<?php

namespace LaravelWhisper\Whisper\Test;

use stdClass;
use LaravelWhisper\Whisper\Test\Stubs\ModelStub;

class ModelTest extends TestCase
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

    public function testCasts()
    {
      $model = new class extends ModelStub {
        protected $casts = [
          'intAttribute' => 'int',
          'floatAttribute' => 'float',
          'stringAttribute' => 'string',
          'boolAttribute' => 'bool',
          'booleanAttribute' => 'boolean',
          'objectAttribute' => 'object',
          'arrayAttribute' => 'array',
          'jsonAttribute' => 'json',
          'dateAttribute' => 'date',
          'datetimeAttribute' => 'datetime',
          'timestampAttribute' => 'timestamp',
        ];

        public function jsonAttributeValue()
        {
          return $this->attributes['jsonAttribute'];
        }
      };

      $model->setDateFormat('Y-m-d H:i:s');
        $model->intAttribute = '3';
        $model->floatAttribute = '4.0';
        $model->stringAttribute = 2.5;
        $model->boolAttribute = 1;
        $model->booleanAttribute = 0;
        $model->objectAttribute = ['foo' => 'bar'];
        $obj = new stdClass;
        $obj->foo = 'bar';
        $model->arrayAttribute = $obj;
        $model->jsonAttribute = ['foo' => 'bar'];
        $model->dateAttribute = '1969-07-20';
        $model->datetimeAttribute = '1969-07-20 22:56:00';
        $model->timestampAttribute = '1969-07-20 22:56:00';
        $this->assertInternalType('int', $model->intAttribute);
        $this->assertInternalType('float', $model->floatAttribute);
        $this->assertInternalType('string', $model->stringAttribute);
        $this->assertInternalType('boolean', $model->boolAttribute);
        $this->assertInternalType('boolean', $model->booleanAttribute);
        $this->assertInternalType('object', $model->objectAttribute);
        $this->assertInternalType('array', $model->arrayAttribute);
        $this->assertInternalType('array', $model->jsonAttribute);
        $this->assertTrue($model->boolAttribute);
        $this->assertFalse($model->booleanAttribute);
        $this->assertEquals($obj, $model->objectAttribute);
        $this->assertEquals(['foo' => 'bar'], $model->arrayAttribute);
        $this->assertEquals(['foo' => 'bar'], $model->jsonAttribute);
        $this->assertEquals('{"foo":"bar"}', $model->jsonAttributeValue());
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $model->dateAttribute);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $model->datetimeAttribute);
        $this->assertEquals('1969-07-20', $model->dateAttribute->toDateString());
        $this->assertEquals('1969-07-20 22:56:00', $model->datetimeAttribute->toDateTimeString());
        $this->assertEquals(-14173440, $model->timestampAttribute);
        $arr = $model->toArray();
        $this->assertInternalType('int', $arr['intAttribute']);
        $this->assertInternalType('float', $arr['floatAttribute']);
        $this->assertInternalType('string', $arr['stringAttribute']);
        $this->assertInternalType('boolean', $arr['boolAttribute']);
        $this->assertInternalType('boolean', $arr['booleanAttribute']);
        $this->assertInternalType('object', $arr['objectAttribute']);
        $this->assertInternalType('array', $arr['arrayAttribute']);
        $this->assertInternalType('array', $arr['jsonAttribute']);
        $this->assertTrue($arr['boolAttribute']);
        $this->assertFalse($arr['booleanAttribute']);
        $this->assertEquals($obj, $arr['objectAttribute']);
        $this->assertEquals(['foo' => 'bar'], $arr['arrayAttribute']);
        $this->assertEquals(['foo' => 'bar'], $arr['jsonAttribute']);
        $this->assertEquals('1969-07-20 00:00:00', $arr['dateAttribute']);
        $this->assertEquals('1969-07-20 22:56:00', $arr['datetimeAttribute']);
        $this->assertEquals(-14173440, $arr['timestampAttribute']);
    }
}
