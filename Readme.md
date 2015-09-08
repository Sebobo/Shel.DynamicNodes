# Dynamic node type manager for Neos CMS

This package allows you to setup dynamic node types via a backend module in Neos CMS.

## Installation

Install `shel/dynamicnodes` with composer:

    composer require shel/dynamicnodes

A separate branch for Neos 1.2.* and Neos 2.0.* support exists.
Composer should automatically select the right one.

## Try it

Use our [DynamicNodes Neos distribution](https://github.com/Sebobo/ShelDynamicNodesDistribution) to quickly create
a project which contains the package. It is used for further development.

## Configuration

You can adjust the functionality of the backend module and the node behaviour to your needs.
See `Configuration/Settings.yaml` for all the settings. 
You can overwrite these settings in your site packages `Settings.yaml` or with the package that
has this package as dependency.

### Adjust default supertype of the dynamic nodes

See `Configuration/NodeTypes.DynamicNode.yaml` for the default supertype of all dynamic nodes.
Overwrite `Shel.DynamicNodes:DynamicNode` in your own package to fit it to your needs.
For example you can change the default icon or add more properties or supertypes.

You can also use this supertype to setup constraints so the new nodes can only be created
where you want it.

### Use your own supertype for the dynamic nodes

Add the following to your `Settings.yaml` and adapt to your needs:

    Shel:
      DynamicNodes:
        defaults:
          superTypes:
            'Vendor.Package:DynamicNodeSuperType': true
  
### Change labels & translate

TODO: implement

### Adjust rendering

See `Resources/Private/TypoScript/Root.ts2`.
The default behaviour is to override the primary content rendering and just render a table with
all dynamic properties.
You can override the prototype of `Shel.DynamicNodes:DynamicNodeContent` to change the template
or completely change the behaviour.

## Usage
 
In the Neos CMS backend go to the modules list and click on `Manage dynamic node types`.
There you can add new nodes and add properties to them.
After each change the node cache is flushed and the changes are effective immediately.
You can use the created node types by adding new documents in the page tree.
There will be a new group called `Dynamic node types`.

It's save to rename nodes and properties as a unique identifier is set when they are created.
Also a unique suffix is added to this identifier so two nodes or properties with the same
label won't collide.

## Roadmap

* Translation / customizeable labels
* Sorting of fields
* Customizeable icons for nodes
* Warning when deleting nodes which are in use
* Import & Export of dynamic node types
* Customizable configuration prototype for dynamic properties

## Related topics:

* https://docs.google.com/document/d/1DXB08TRH4AJkjQ3Hz1OcWUwD4gEATy4qJ-MgQRkEyNo/edit#heading=h.3tokhzop3inv
