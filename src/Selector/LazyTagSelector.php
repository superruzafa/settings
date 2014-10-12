<?php

namespace Superruzafa\Settings\Selector;

class LazyTagSelector extends TagSelector
{
    /** @inheritdoc */
    protected function doTagSelect(array $metadata)
    {
        $tags = $this->classifyTags($metadata);
        $commonTags = array_intersect($this->tags, $tags['all']);
        return count($commonTags) == count($this->tags);
    }
}
