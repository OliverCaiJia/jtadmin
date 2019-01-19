<?php
namespace Tests\Strategies;

use App\Strategies\ChannelStrategy;
use Tests\TestCase;

class ChannelStrategyTest extends TestCase
{
    public function testShouldReturnAvailableHashId()
    {
        $this->assertEquals(6, strlen(ChannelStrategy::generateHashId(1)));
    }
}
