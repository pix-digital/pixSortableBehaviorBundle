<?php
/*
 * This file is part of the pixSortableBehaviorBundle.
 *
 * (c) Nicolas Ricci <nicolas.ricci@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pix\SortableBehaviorBundle\Services;

abstract class PositionHandler
{
    protected $positionField;

    abstract public function getLastPosition($entity);

    /**
     * @param mixed $positionField
     */
    public function setPositionField($positionField)
    {
        $this->positionField = $positionField;
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function getPositionFieldByEntity($entity)
    {
        if (is_object($entity)) {
            $entity = \Doctrine\Common\Util\ClassUtils::getClass($entity);
        }
        if (isset($this->positionField['entities'][$entity])) {
            return $this->positionField['entities'][$entity];
        } else {
            return $this->positionField['default'];
        }
    }

    /**
     * @param $object
     * @param $position
     * @param $lastPosition
     *
     * @return int
     */
    public function getPosition($object, $position, $lastPosition)
    {
        $getter = sprintf('get%s', ucfirst($this->getPositionFieldByEntity($object)));
        $newPosition = 0;

        switch ($position) {
            case 'up' :
                if ($object->{$getter}() > 0) {
                    $newPosition = $object->{$getter}() - 1;
                }
                break;

            case 'down':
                if ($object->{$getter}() < $lastPosition) {
                    $newPosition = $object->{$getter}() + 1;
                }
                break;

            case 'top':
                if ($object->{$getter}() > 0) {
                    $newPosition = 0;
                }
                break;

            case 'bottom':
                if ($object->{$getter}() < $lastPosition) {
                    $newPosition = $lastPosition;
                }
                break;
        }

        return $newPosition;
    }
}
