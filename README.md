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
            AppBundle/Entity/Baz: [ group ]
            
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
                        'template' => 'AppBundle:Admin:_sort.html.twig',
                        'enable_top_bottom_buttons' => true,
                    ),
                ),
            ))
        ;
    }
```    
