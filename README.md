Settings
========

[![Build Status](https://travis-ci.org/superruzafa/settings.svg?branch=master)](https://travis-ci.org/superruzafa/settings)

Settings is a library for manage collection of elements.
Each element is included into the collection along with some metadata.
This metadata could be used later for enumerate a subset of elements.
The elements are selected using selectors.

Built-in selectors
------------------

Settings comes with some predefined selectors:

### Tag selectors

This kind of selector allow to store the elements with some associated tags.
There is two classes of tags:

 - Required tags: `'required'`, `'necessary`
 - Optional tags (between brackets): `'[optional]'`, `'[redundant]'`
  
Both required and optional tags have special meanings depending on th type of tag selector.
Optional tags are usually used for disambiguation purposes.

#### Strict tag selector

This selector selects only those items whose metatags intersect with the selector tags:

``` php
<?php

use Superruzafa\Settings\Collection;
use Superruzafa\Settings\Selector\StrictTagSelector;

$collection = new Collection();
$collection->add('item1', array('tag1', '[tag2]', 'tag3'));
$collection->add('item2', array('tag1', '[tag2]'));
$collection->add('item3', array('[tag1]', 'tag2', 'foo'));
$collection->add('item4', array('[tag1]', 'tag2', '[bar]'));

$selector = new StrictTagSelector('tag1', 'tag2');
$selected = $collection->setSelector($selector)->select();

// $selected = ['item2', 'item4']
// item1 wasn't selected because 'tag3' wasn't included in the selector's tags.
// item2 was selected because of explicit matching of 'tag1' and 'tag2'
// item3 wasn't selected because 'foo' wasn't included in the selector's tags.
// item4 matched tag1 and tag2, 'bar' is an optional tag and it's not necessary to match it explicitlly.
```

### Other selectors

#### AlwaysSelector
This selector always selects every element in the collection.

``` php
$selector = new AlwaysSelector();
$selected = $collection->setSelector($selector)>select();
// $selected = all elements in the collection
```

#### NeverSelector
As opposite to the AlwaysSelector, this selector selects no element in the collection.

``` php
$selector = new NeverSelector();
$selected = $collection->setSelector($selector)>select();
// $selected = array()
```

### Selection methods

Each selector comes with one of these operations:

- `select()` Selects all those elements selected by the selector.
- `selectOne()` Selects the first element selected by the selector
- `discard()` Select all those elements not selected by the selector.
- `discardOne()` Select the first element not selected by the selector.
