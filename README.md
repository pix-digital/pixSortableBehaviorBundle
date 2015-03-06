pixSortableBehaviorBundle
=========================

Offers a sortable feature for your Symfony2 admin listing

Implementation for the Sonata Admin bundle explained in the cookbook

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
            AcmeBundle/Entity/Foobar: order
            AcmeBundle/Entity/Baz: rang
```
