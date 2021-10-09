<?php

namespace Pix\SortableBehaviorBundle\Twig;

use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Description of ObjectPositionExtension
 *
 * @author Volker von Hoesslin <volker.von.hoesslin@empora.com>
 */
class ObjectPositionExtension extends AbstractExtension
{
    /**
     * @var PositionHandler
     */
    private $positionHandler;

    public function __construct(PositionHandler $positionHandler)
    {
        $this->positionHandler = $positionHandler;
    }

    public function getFunctions(): array
    {
        return array(
            new TwigFunction('currentObjectPosition', array($this, 'currentPosition')),
            new TwigFunction('lastPosition', array($this, 'lastPosition'))
        );
    }

    public function currentPosition($entity): int
    {
        return $this->positionHandler->getCurrentPosition($entity);
    }

    public function lastPosition($entity): int
    {
        return $this->positionHandler->getLastPosition($entity);
    }
}
