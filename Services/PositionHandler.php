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
    public function getPositionFieldByEntity($entity) {
        if(isset($this->positionField['entities'][$entity])) {
            return $this->positionField['entities'][$entity];
        } else {
            return $this->positionField['default'];
        }
    }

    /**
     * @param $object
     * @param $position
     * @param $last_position
     *
     * @return int
     */
    public function getPosition($object, $position, $last_position)
    {
        $getter = sprintf('get%s', ucfirst($this->getPositionFieldByEntity(\Doctrine\Common\Util\ClassUtils::getClass($object))));
        switch ($position) {
            case 'up' :
                if ($object->{$getter}() > 0) {
                    $new_position = $object->{$getter}() - 1;
                }
                break;

            case 'down':
                if ($object->{$getter}() < $last_position) {
                    $new_position = $object->{$getter}() + 1;
                }
                break;

            case 'top':
                if ($object->{$getter}() > 0) {
                    $new_position = 0;
                }
                break;

            case 'bottom':
                if ($object->{$getter}() < $last_position) {
                    $new_position = $last_position;
                }
                break;

            default: $new_position = 0;
        }

        return $new_position;

    }


}
