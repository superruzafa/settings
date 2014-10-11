<?php

namespace Superruzafa\Settings\Selector;

use Superruzafa\Settings\Selector;
use Superruzafa\Settings\SelectorException;

abstract class TagSelector implements Selector
{
    /** @var string[] */
    protected $tags;

    /**
     * @param   string[]|string    $arg1,...
     * @throws  SelectorException
     */
    final public function __construct($arg1)
    {
        if (is_array($arg1)) {
            if (empty($arg1)) {
                throw new SelectorException('TagSelector needs at least one tag');
            }
            $this->tags = $arg1;
        } else {
            $this->tags = func_get_args();
        }
        $this->tags = array_unique($this->tags);
    }

    /**
     * @return string[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param   array   $tags
     * @return  array[]
     */
    protected function classifyTags(array $tags)
    {
        $classified = array(
            'optional' => array(),
            'required' => array(),
            'all'      => array()
        );

        foreach (array_unique($tags) as $tag) {
            if (preg_match('/^\[(\w+)\]$/', $tag, $matches)) {
                $classified['optional'][] = $matches[1];
                $classified['all'][] = $matches[1];
            } else {
                $classified['required'][] = $tag;
                $classified['all'][] = $tag;
            }
        }
        return $classified;
    }

    /** @inheritdoc */
    final public function select($metadata)
    {
        if (!is_array($metadata)) {
            throw new SelectorException('TagSelector can only receive arrays as select input');
        }
        return $this->doTagSelect($metadata);
    }

    /**
     * @param   string[]    $metadata
     * @return  bool
     */
    abstract protected function doTagSelect(array $metadata);
} 
