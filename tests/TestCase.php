<?php

namespace LaravelWhisper\Whisper\Test;

use Mockery as m;
use Carbon\Carbon;
use LaravelWhisper\Whisper\Model;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function setup()
    {
        parent::setup();
        Carbon::setTestNow(Carbon::now());
    }

    public function tearDown()
    {
        parent::tearDown();
        m::close();
        Carbon::setTestNow(null);
        Model::unsetEventDispatcher();
        Carbon::resetToStringFormat();
    }
}
