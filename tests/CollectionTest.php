<?php

namespace Superruzafa\Settings;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /** @var Selector */
    private static $neverSelector;

    /** @var Selector */
    private static $alternatingSelector;

    /** @var Selector */
    private static $alwaysSelector;

    public static function setUpBeforeClass()
    {
        self::$neverSelector = self::buildConstantSelector(false);
        self::$alwaysSelector = self::buildConstantSelector(true);
        self::$alternatingSelector = self::buildAlternatingSelector();
    }

    /** @test */
    public function emptyCollectionWithNeverSelecting()
    {
        $selected = $this->setupEmptyCollection()->setSelector(self::$neverSelector)->select();
        $this->assertEquals(array(), $selected);
    }

    /** @test */
    public function emptyCollectionWithAlternatingSelecting()
    {
        $selected = $this->setupEmptyCollection()->setSelector(self::$alternatingSelector)->select();
        $this->assertEquals(array(), $selected);
    }

    /** @test */
    public function emptyCollectionWithAlwaysSelecting()
    {
        $selected = $this->setupEmptyCollection()->setSelector(self::$alwaysSelector)->select();
        $this->assertEquals(array(), $selected);
    }

    /** @test */
    public function emptyCollectionWithNeverDiscarding()
    {
        $discarded = $this->setupEmptyCollection()->setSelector(self::$neverSelector)->discard();
        $this->assertEquals(array(), $discarded);
    }

    /** @test */
    public function emptyCollectionWithAlternatingDiscarding()
    {
        $discarded = $this->setupEmptyCollection()->setSelector(self::$alternatingSelector)->discard();
        $this->assertEquals(array(), $discarded);
    }

    /** @test */
    public function emptyCollectionWithAlwaysDiscarding()
    {
        $discarded = $this->setupEmptyCollection()->setSelector(self::$alwaysSelector)->discard();
        $this->assertEquals(array(), $discarded);
    }

    /** @test */
    public function nonEmptyCollectionWithNeverSelecting()
    {
        $selected = $this->setupNonEmptyCollection()->setSelector(self::$neverSelector)->select();
        $this->assertEquals(array(), $selected);
    }

    /** @test */
    public function nonEmptyCollectionWithAlternatingSelecting()
    {
        $selected = $this->setupNonEmptyCollection()->setSelector(self::$alternatingSelector)->select();
        $this->assertEquals(array('B', 'D'), $selected);
    }

    /** @test */
    public function nonEmptyCollectionWithAlwaysSelecting()
    {
        $selected = $this->setupNonEmptyCollection()->setSelector(self::$alwaysSelector)->select();
        $this->assertEquals(array('A', 'B', 'C', 'D'), $selected);
    }

    /** @test */
    public function nonEmptyCollectionWithNeverDiscarding()
    {
        $discarded = $this->setupNonEmptyCollection()->setSelector(self::$neverSelector)->discard();
        $this->assertEquals(array('A', 'B', 'C', 'D'), $discarded);
    }

    /** @test */
    public function nonEmptyCollectionWithAlternatingDiscarding()
    {
        $discarded = $this->setupNonEmptyCollection()->setSelector(self::$alternatingSelector)->discard();
        $this->assertEquals(array('A', 'C'), $discarded);
    }

    /** @test */
    public function nonEmptyCollectionWithAlwaysDiscarding()
    {
        $discarded = $this->setupNonEmptyCollection()->setSelector(self::$alwaysSelector)->discard();
        $this->assertEquals(array(), $discarded);
    }

    /**
     * @return Collection
     */
    private static function setupEmptyCollection()
    {
        return new Collection();
    }

    /**
     * @return Collection
     */
    private static function setupNonEmptyCollection()
    {
        $collection = new Collection();
        return $collection->add('A', 1)->add('B', 2)->add('C', 3)->add('D', 4);
    }

    private static function buildConstantSelector($outcome)
    {
        $mockGenerator = new \PHPUnit_Framework_MockObject_Generator;
        $selector = $mockGenerator->getMockForAbstractClass('Superruzafa\Settings\Selector');
        $selector
            ->expects(self::any())
            ->method('select')
            ->will(self::returnValue($outcome));

        return $selector;
    }

    private static function buildAlternatingSelector()
    {
        $alternating = true;
        $mockGenerator = new \PHPUnit_Framework_MockObject_Generator;
        $selector = $mockGenerator->getMockForAbstractClass('Superruzafa\Settings\Selector');
        $selector
            ->expects(self::any())
            ->method('select')
            ->will(self::returnCallback(function () use (&$alternating) {
                return $alternating = !$alternating;
            }));

        return $selector;
    }
}
