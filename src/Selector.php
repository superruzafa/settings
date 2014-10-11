<?php

namespace Superruzafa\Settings;

interface Selector
{
    /**
     * @param   mixed   $input
     * @return  bool
     */
    public function doSelect($input);
}
