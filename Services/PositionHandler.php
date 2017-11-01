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

use Doctrine\Common\Util\ClassUtils;

abstract class PositionHandler
{
    /**
     * From config
     *
     * @var array
     */
    protected $positionField;

    /**
     * From config
     *
     * @var array
     */
    private $sortableGroups;

    /**
     * @param object $entity
     * @return int
     */
    abstract public function getLastPosition($entity);

    /**
     * @param array $positionField
     */
    public function setPositionField(array $positionField)
    {
        $this->positionField = $positionField;
    }

    /**
     * @param array $sortableGroups
     */
    public function setSortableGroups(array $sortableGroups)
    {
        $this->sortableGroups = $sortableGroups;
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function getPositionFieldByEntity($entity)
    {
        if (is_object($entity)) {
            $entity = ClassUtils::getClass($entity);
        }

        if (isset($this->positionField['entities'][$entity])) {
            return $this->positionField['entities'][$entity];

        } else {
            return $this->positionField['default'];
        }
    }

    /**
     * @param $entity
     *
     * @return array
     */
    public function getSortableGroupsFieldByEntity($entity)
    {
        if (is_object($entity)) {
            $entity = ClassUtils::getClass($entity);
        }

        $groups = [];
        if (isset($this->sortableGroups['entities'][$entity])) {
            $groups = $this->sortableGroups['entities'][$entity];
        }

        return $groups;
    }

    /**
     * @param $entity
     *
     * @return int
     */
    public function getCurrentPosition($entity)
    {
        $getter = sprintf('get%s', ucfirst($this->getPositionFieldByEntity($entity)));

        return $entity->{$getter}();
    }

    /**
     * @param object $object
     * @param string $movePosition
     * @param int    $lastPosition
     *
     * @return int
     */
    public function getPosition($object, $movePosition, $lastPosition)
    {
        $currentPosition = $this->getCurrentPosition($object);
        $newPosition = 0;

        switch ($movePosition) {
            case 'up' :
                if ($currentPosition > 0) {
                    $newPosition = $currentPosition - 1;
                }
                break;

            case 'down':
                if ($currentPosition < $lastPosition) {
                    $newPosition = $currentPosition + 1;
                }
                break;

            case 'top':
                if ($currentPosition > 0) {
                    $newPosition = 0;
                }
                break;

            case 'bottom':
                if ($currentPosition < $lastPosition) {
                    $newPosition = $lastPosition;
                }
                break;

            default:
                if (is_numeric($movePosition)) {
                    $newPosition = (int) $movePosition;
                }

        }

        return $newPosition;
    }
}
