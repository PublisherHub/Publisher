
# Publisher - Parameter Selections

## Components

### Selection
A `Selection` is a simple model that contains choices for a parameter.

### SelectionCollection
A `SelectionCollection` manages a group of `Selection`s for an `Entry`. It contains the `Selection`s in a chronological order and the made decisions for parameter values.
If a previous decision is changed, it'll also reset the following decisions.
Even so it manages a group of `Selection`s for an `Entry`, it doesn't contain any specific requirements regarding required requests or parameters. This information is placed in a `SelectorDefinition`.

### SelectorDefinition
A `SelectorDefinition` defines which steps or more precisely `Request`s are necessary to retrieve certain parameters that an `Entry` requires to be published.

A *ProviderPageEntry* may require a pageId and an additional access token.
To retrieve those parameters multiple requests may be necessary.
Those steps are defined chronological in the a corresponding `SelectorDefinition` e.g. *ProviderPageSelectorDefinition*.

### Selector
A `Selector` is responsible to execute the current `Request`s based on the configuration given by a `SelectorDefinition` and the state of a `SelectionCollection`.

### SelectorFactory
After you setup your `SelectorFactory` properly, you only need the `Entry` ID to create a `Selection`.

### SelectionCollectionArrayTransformer
A `SelectionCollectionArrayTransformer` can map the data of an SelectionCollection to an array and recreate a SelectionCollection based on this data.

### SelectorManager
A `SelectorManager` is responsible to manage a group of `Selector`s and collecting their parameters simultaneously. It uses a `SelectorFactory` to create the `Selectors` and a `SelectionCollectionArrayTransformer` to recreate the `SelectionCollection`s between multiple requests. If you don't want the user to choose parameters for each `Entry` at the same time, you should use the `Selector`s directly instead.


## Workflow: Obtain Parameters

### Setup
```php
$entryIds = ['ProviderPage', 'ServiceUser'];

/** @var Publisher\Selector\Manager\SelectorManager */
$selectorManager;
```

### Initial Request

In the beginning no `Selection` will be set. We need to initialize the first `Selection`s with `$selectorManager->executeCurrentSteps()`. For the case that we don't need to obtain any parameters, we'll check, if the parameters are already set, before executing the current step.

```php
$selectorManager->setupSelectors($entryIds);

// not each Entry requires parameters,
if (!$selectorManager->areAllParametersSet()) {
	$selectorManager->executeCurrentSteps();
    
	$selectionCollection = $selectorManager->getCollectionsAsArray();
    /* save $selectionCollection e.g. in a session
     * to retrieve the SelectionCollections later on
     */
     
    // create a form for the user
}
```
If you got any `SelectionCollection`s, you can use their data to create a form, so that the user can choose and confirm further parameters. You can use the data array or instead the objects themselves that are returned by `$selectorManager->getCollections()`.


### Following Requests

When you obtained the users input, make sure to split it per `Entry` ID.
Otherwise the `SelectorManager` won't be able to forward the data to the right `Selector` instance.

```php
// Example for the retrieved SelectionCollection data from the session
$retrievedSelectionCollectionData = [
    'ServiceUser' => [],
    'ProviderPage' => [
        'decisions' => [ /* ... */ ],
        'selections' => [
        	0 => [
              'name' => 'pageId',
              'choices' => ['option1' => 'foo', 'option2' => 'bar']
            ],
            1 => [ /* ... */ ]
        ]
    ]
];

$selectorManager->setupSelectors($entryIds, $retrievedSelectionCollectionData);

$parameters = [
	'ServiceUser' => [],
	'ProviderPage' => ['pageId' => 'foo']
];

// update the current state of the selections
$selectorManager->updateSelectors($parameters);
        
if (!$selectorManager->areAllParametersSet()) {
	$selectorManager->executeCurrentSteps();
    
	$selectionCollection = $selectorManager->getCollectionsAsArray();
    /* save $selectionCollection e.g. in a session
     * to retrieve the SelectionCollections later on
     */
     
    // create a form for the user
}
```

### Get All Parameters
When all parameters are set, it is time to get them all.
`$selectorManager->getAllParameters()` will return you all required parameters grouped by their `Entry`s ID.