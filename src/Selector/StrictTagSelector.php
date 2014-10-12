<?php

namespace Superruzafa\Settings\Selector;

class StrictTagSelector extends TagSelector
{
    protected function doTagSelect(array $metadata)
    {
        $tags = $this->classifyTags($metadata);
        $commonTags = array_intersect($this->tags, $tags['all']);
        $commonRequiredTags = array_intersect($this->tags, $tags['required']);

        return count($commonTags) == count($this->tags) && count($commonRequiredTags) == count($tags['required']);
    }
}
