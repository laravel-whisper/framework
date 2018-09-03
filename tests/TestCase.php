<?php

namespace LaravelWhisper\Whisper\Test;

use Mockery as m;
use Illuminate\Support\Carbon;
use LaravelWhisper\Whisper\Whisperer;
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
	    Whisperer::unsetEventDispatcher();
        Carbon::resetToStringFormat();
    }
}
