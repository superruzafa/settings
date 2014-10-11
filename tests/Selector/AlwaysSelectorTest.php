<?php

namespace Superruzafa\Settings\Selector;

class AlwaysSelectorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function alwaysSelect()
    {
        $selector = new AlwaysSelector();
        $this->assertTrue($selector->select('whatever'));
    }
}
