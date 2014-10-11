<?php

namespace Superruzafa\Settings;

interface Selector
{
    /**
     * @param   mixed   $metadata
     * @return  bool
     */
    public function select($metadata);
}
