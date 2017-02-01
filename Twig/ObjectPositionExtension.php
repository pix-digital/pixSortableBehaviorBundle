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
            new \Twig_SimpleFunction('currentObjectPosition', array($this, 'currentPosition')),
            new \Twig_SimpleFunction('lastPosition', array($this, 'lastPosition'))
        );
    }

    /**
     * @return int
     */
    public function currentPosition($entity) {
        return $this->positionHandler->getCurrentPosition($entity);
    }

    /**
     * @return int
     */
    public function lastPosition($entity) {
        return $this->positionHandler->getLastPosition($entity);
    }
}
