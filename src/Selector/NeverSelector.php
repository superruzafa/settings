<?php

namespace Superruzafa\Settings\Selector;

use Superruzafa\Settings\Selector;

class NeverSelector implements Selector
{
    /** @inheritdoc */
    public function select($metadata)
    {
        return false;
    }
}

