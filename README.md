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

// The selector would select those items containing both `tag1` and `tag2` tags.
// If the item defines any other required tag that is not matched then the item is discarded.
$selector = new StrictTagSelector('tag1', 'tag2');
$selected = $collection->setSelector($selector)->select();

// $selected = ['item2', 'item4']
```

#### Lazy tag selector

This selector select those items whose metatags are a superset of the selector tags.

``` php
<?php

use Superruzafa\Settings\Collection;
use Superruzafa\Settings\Selector\LazyTagSelector;

$collection = new Collection();
$collection->add('item1', array('tag1', '[tag2]', 'tag3'));
$collection->add('item2', array('tag1', '[tag2]'));
$collection->add('item3', array('[tag1]', 'tag2', 'foo'));
$collection->add('item4', array('[tag1]', 'foo', '[bar]'));

// The selector would select those items containing at least both `tag1` and `tag2` tags.
// Other tags (required or optional) doesn't affect.
$selector = new LazyTagSelector('tag1', 'tag2');
$selected = $collection->setSelector($selector)->select();

// $selected = ['item1', 'item2', 'item3']
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
