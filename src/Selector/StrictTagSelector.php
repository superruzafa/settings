<?php

namespace Superruzafa\Settings\Selector;

class StrictTagSelector extends TagSelector
{
    protected function doTagSelect(array $candidate)
    {
        $tags = $this->classifyTags($candidate);
        $i1 = array_intersect($this->tags, $tags['all']);
        $i2 = array_intersect($this->tags, $tags['required']);

        return count($i1) == count($this->tags) && count($i2) == count($tags['required']);
    }
}
