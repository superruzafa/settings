<?php

namespace Superruzafa\Settings;

use Superruzafa\Settings\Selector\AlwaysSelector;

class Collection implements Selectable
{
    /** @var array[] */
    private $items = array();

    /** @var Selector */
    private $selector;

    /**
     * Creates a new Collection object
     */
    public function __construct()
    {
        $this->selector = new AlwaysSelector();
    }

    /** @inheritdoc */
    public function getSelector()
    {
        return $this->selector;
    }

    /** @inheritdoc */
    public function setSelector(Selector $selector)
    {
        $this->selector = $selector;
        return $this;
    }

    /** @inheritdoc */
    public function select()
    {
        $selector = $this->selector;
        $callback = function ($metadata) use ($selector) {
            return $selector->select($metadata);
        };
        return $this->walkItems($callback, count($this->items));
    }

    /** @inheritdoc */
    public function selectOne()
    {
        $selector = $this->selector;
        $callback = function ($metadata) use ($selector) {
            return $selector->select($metadata);
        };
        return $this->walkItems($callback, 1);
    }

    /** @inheritdoc */
    public function discard()
    {
        $selector = $this->selector;
        $callback = function ($metadata) use ($selector) {
            return !$selector->select($metadata);
        };
        return $this->walkItems($callback, count($this->items));
    }

    /** @inheritdoc */
    public function discardOne()
    {
        $selector = $this->selector;
        $callback = function ($metadata) use ($selector) {
            return !$selector->select($metadata);
        };
        return $this->walkItems($callback, 1);
    }

    /**
     * Walks the item collection gathering those items selected by a callback.
     *
     * @param   \Closure    $callback   Callback used to either select items or not.
     * @param   int         $count      Maximum number of items to be gathered
     * @return  array
     */
    private function walkItems(\Closure $callback, $count)
    {
        $selected = array();
        reset($this->items);
        list(, list($item, $metadata)) = each($this->items);
        while ($count > 0 && $item) {
            if (call_user_func($callback, $metadata)) {
                $selected[] = $item;
                --$count;
            }
            list(, list($item, $metadata)) = each($this->items);
        }

        return $selected;
    }

    /**
     * Adds an item to the collection
     *
     * @param   mixed   $item       Item itself
     * @param   mixed   $metadata   Metadata used later for selecting.
     * @return  Collection
     */
    public function add($item, $metadata)
    {
        $this->items[] = array($item, $metadata);
        return $this;
    }
}
