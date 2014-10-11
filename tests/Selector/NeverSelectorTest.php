<?php

namespace Superruzafa\Settings\Selector;

class NeverSelectorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function neverSelect()
    {
        $selector = new NeverSelector();
        $this->assertFalse($selector->select('whatever'));
    }
}
