<?php

namespace Superruzafa\Settings\Selector;

class LazyTagSelector extends TagSelector
{
    /** @inheritdoc */
    protected function doTagSelect(array $metadata)
    {
        $tags = $this->classifyTags($metadata);
        $i1 = array_intersect($this->tags, $tags['all']);
        return count($i1) == count($this->tags);
    }
}
