<?php

namespace Pix\SortableBehaviorBundle\Twig;

use Pix\SortableBehaviorBundle\Services\PositionHandler;

/**
 * Description of ObjectPositionExtension
 * 
 * @author Volker von Hoesslin <volker.von.hoesslin@empora.com>
 */
class ObjectPositionExtension extends \Twig_Extension
{
    const NAME = 'sortableObjectPosition';

    /**
     * PositionHandler
     */
    private $positionHandler;

    /**
     * @param PositionHandler $positionHandler
     */
    public function __construct(PositionHandler $positionHandler)
    {
        $this->positionHandler = $positionHandler;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('currentObjectPosition', function ($entity) {
                $getter = sprintf('get%s', ucfirst($this->positionHandler->getPositionFieldByEntity($entity)));

                return $entity->{$getter}();
            }),

            new \Twig_SimpleFunction('lastPosition', function ($entity) {
                return $this->positionHandler->getLastPosition($entity);
            }),
        );
    }
}
