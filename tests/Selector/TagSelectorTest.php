<?php

namespace Superruzafa\Settings\Selector;

class TagSelectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group exceptions
     */
    public function createTagSelectorWithEmptyArray()
    {
        $this->setExpectedException('Superruzafa\Settings\SelectorException', 'TagSelector needs at least one tag');
        $this
            ->getMockBuilder('Superruzafa\Settings\Selector\TagSelector')
            ->setConstructorArgs(array(array()))
            ->getMockForAbstractClass();
    }

    /** @test */
    public function createTagSelectorWithNonEmptyArray()
    {
        $selector = $this
            ->getMockBuilder('Superruzafa\Settings\Selector\TagSelector')
            ->setConstructorArgs(array(array('tag1', 'tag2', 'tag3')))
            ->getMockForAbstractClass();

        $this->assertEquals($selector->getTags(), array('tag1', 'tag2', 'tag3'));
    }

    /** @test */
    public function createTagSelectorWithArgs()
    {
        $selector = $this
            ->getMockBuilder('Superruzafa\Settings\Selector\TagSelector')
            ->setConstructorArgs(array('tag1', 'tag2', 'tag3'))
            ->getMockForAbstractClass();

        $this->assertEquals($selector->getTags(), array('tag1', 'tag2', 'tag3'));
    }

    /**
     * @test
     * @group exceptions
     */
    public function selectWithNonArray()
    {
        $selector = $this
            ->getMockBuilder('Superruzafa\Settings\Selector\TagSelector')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->setExpectedException('Superruzafa\Settings\SelectorException', 'TagSelector can only receive arrays as select input');
        $selector->select('foo');
    }

    /** @test */
    public function select()
    {
        $selector = $this
            ->getMockBuilder('Superruzafa\Settings\Selector\TagSelector')
            ->disableOriginalConstructor()
            ->setMethods(array('doTagSelect'))
            ->getMockForAbstractClass();

        $selector
            ->expects($this->once())
            ->method('doTagSelect')
            ->with(array('foo', 'bar'))
            ->will($this->returnValue(true));
        $this->assertTrue($selector->select(array('foo', 'bar')));
    }
}
