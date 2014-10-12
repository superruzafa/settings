<?php

namespace Superruzafa\Settings\Selector;

class LazyTagSelectorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function noMatching()
    {
        $selector = new LazyTagSelector('tag1', 'tag2');
        $this->assertFalse($selector->select(array('foo')));
    }

    /** @test */
    public function noMatchingPartialTags()
    {
        $selector = new LazyTagSelector('tag1', 'tag2');
        $this->assertFalse($selector->select(array('tag1', 'foo')));
    }

    /** @test */
    public function exactMatchingRequiredTags()
    {
        $selector = new LazyTagSelector('tag1', 'tag2');
        $this->assertTrue($selector->select(array('tag1', 'tag2')));
    }

    /** @test */
    public function exactMatchingOptionalTags()
    {
        $selector = new LazyTagSelector('tag1', 'tag2');
        $this->assertTrue($selector->select(array('[tag1]', '[tag2]')));
    }

    /** @test */
    public function exactMatchingMixedCase()
    {
        $selector = new LazyTagSelector('tag1', 'tag2');
        $this->assertTrue($selector->select(array('tag1', '[tag2]')));
    }

    /** @test */
    public function enoughMatchingRequiredTags()
    {
        $selector = new LazyTagSelector('tag1');
        $this->assertTrue($selector->select(array('tag1', 'tag4')));
    }

    /** @test */
    public function enoughMatchingOptionalTags()
    {
        $selector = new LazyTagSelector('tag1');
        $this->assertTrue($selector->select(array('[tag1]', 'tag4')));
    }
}
