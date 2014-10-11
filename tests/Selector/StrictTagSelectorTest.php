<?php

namespace Superruzafa\Settings;

use Superruzafa\Settings\Selector\StrictTagSelector;

class StrictTagSelectorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function noMatching()
    {
        $selector = new StrictTagSelector('tag1', 'tag2', 'tag3');
        $this->assertFalse($selector->select(array('foo')));
    }

    /** @test */
    public function tooFewMatchingTags()
    {
        $selector = new StrictTagSelector('tag1', 'tag2', 'tag3');
        $this->assertFalse($selector->select(array('tag1')));
    }

    /** @test */
    public function tooManyMatchingTags()
    {
        $selector = new StrictTagSelector('tag1', 'tag2');
        $this->assertFalse($selector->select(array('tag4', 'tag3', 'tag2', 'tag1')));
    }

    /** @test */
    public function exactMatching()
    {
        $selector = new StrictTagSelector('tag1', 'tag2', 'tag3');
        $this->assertTrue($selector->select(array('tag1', 'tag3', 'tag2')));
    }

    /** @test */
    public function exactMatchingIncludingOptionalTags()
    {
        $selector = new StrictTagSelector('tag1', 'tag2', 'tag3');
        $this->assertTrue($selector->select(array('tag3', '[tag2]', '[tag1]')));
    }

    /** @test */
    public function exactMatchingWhenAllTagsAreOptional()
    {
        $selector = new StrictTagSelector('tag1', 'tag2', 'tag3');
        $this->assertTrue($selector->select(array('[tag1]', '[tag3]', '[tag2]')));
    }

    /** @test */
    public function exactMatchingWhenAllTagsAreOptionalAndThereIsMoreOptionalTags()
    {
        $selector = new StrictTagSelector('tag1', 'tag2', 'tag3');
        $this->assertTrue($selector->select(array('[tag4]', '[tag1]', '[tag3]', '[tag2]')));
    }

    /** @test */
    public function exactMatchingTagsExcludingOptionalTags()
    {
        $selector = new StrictTagSelector('tag1', 'tag2', 'tag3');
        $this->assertTrue($selector->select(array('[tag4]', 'tag3', 'tag2', 'tag1')));
    }

    /** @test */
    public function matchesWhenSelectorContainsDuplicatedTags()
    {
        $selector = new StrictTagSelector('tag1', 'tag2', 'tag3', 'tag2', 'tag3');
        $this->assertTrue($selector->select(array('tag3', 'tag2', 'tag1')));
    }

    /** @test */
    public function matchesWhenMetadataContainsDuplicatedTags()
    {
        $selector = new StrictTagSelector('tag1', 'tag2', 'tag3');
        $this->assertTrue($selector->select(array('tag3', 'tag2', 'tag3', 'tag3', 'tag1')));
    }

    /** @test */
    public function caseSensitiveNonMatching()
    {
        $selector = new StrictTagSelector('tag1');
        $this->assertFalse($selector->select(array('TAG1')));
    }
}
