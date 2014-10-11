<?php

namespace Superruzafa\Settings;

interface Selectable
{
    /**
     * Gets the selector used for select elements.
     *
     * @return Selector
     */
    public function getSelector();

    /**
     * Sets the selector used to select elements.
     * @param   Selector    $selector
     * @return  Selectable
     */
    public function setSelector(Selector $selector);

    /**
     * @return mixed[]
     */
    public function select();

    /**
     * @return mixed
     */
    public function selectOne();

    /**
     * @return mixed[]
     */
    public function discard();

    /**
     * @return mixed
     */
    public function discardOne();
}
