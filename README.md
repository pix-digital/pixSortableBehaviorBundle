pixSortableBehaviorBundle
=========================

Offers a sortable feature for your Symfony2 admin listing

### SonataAdminBundle implementation

The SonataAdminBundle provides a cookbook article here :

https://github.com/sonata-project/SonataAdminBundle/blob/master/Resources/doc/cookbook/recipe_sortable_listing.rst

### Configuration

By default, this extension works with Doctrine ORM, but you can choose to use Doctrine MongoDB by defining the driver configuration : 

``` yaml
# app/config/config.yml
pix_sortable_behavior:
    db_driver: mongodb # default value : orm
    position_field:
        default: sort #default value : position
        entities:
            AppBundle/Entity/Foobar: order
            AppBundle/Entity/Baz: rang
    sortable_groups:
        entities:
            AppBundle/Entity/Baz: [ group ] #Sortable groups goes here or in your doctrine mapping if you use Gedmo
            
```

#### Use a draggable list instead of up/down buttons
In order to use a draggable list instead of up/down buttons, change the template in the ```move``` action to ```PixSortableBehaviorBundle:Default:_sort_drag_drop.html.twig```.

```php
<?php

    // ClientAdmin.php
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('enabled')
            ->add('_action', null, array(
                'actions' => array(
                    'move' => array(
                        'template' => 'PixSortableBehaviorBundle:Default:_sort_drag_drop.html.twig',
                        'enable_top_bottom_buttons' => true, //optional
                        'groups' => array('group') //only if you've defined a sortable group for your entity in the config
                    ),
                ),
            ))
        ;
    }
```    
Also include the JavaScript needed for this to work, in your ```theme.yml``` file, add these two lines:
```yml
    //...
    javascripts:
        - bundles/pixsortablebehavior/js/jquery-ui.min.js // if you haven't got jQuery UI yet.
        - bundles/pixsortablebehavior/js/init.js
```

Adding the JavaScript and the template, will give you the possibility to drag items in a tablelist.
In case you need it, this plugin fires to jQuery events when dragging is done on the ```$(document)``` element, so if you want to add custom notification, that is possible.
```
pixSortableBehaviorBundle.success
pixSortableBehaviorBundle.error
```
#### Disable top and bottom buttons
```php
<?php

    // ClientAdmin.php
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('enabled')
            ->add('_action', null, array(
                'actions' => array(
                    'move' => array(
                        'template' => 'PixSortableBehaviorBundle:Default:_sort.html.twig',
                        'enable_top_bottom_buttons' => false, //Disabled buttons
                        'groups' => array('group') //only if you've defined a sortable group for your entity in the config
                    ),
                ),
            ))
        ;
    }
```    
