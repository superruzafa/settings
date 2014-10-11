<?php

namespace Superruzafa\Settings\Selector;

use Superruzafa\Settings\Selector;

class AlwaysSelector implements Selector
{
    /**
     * @inheritdoc
     */
    public function select($metadata)
    {
        return true;
    }
}

