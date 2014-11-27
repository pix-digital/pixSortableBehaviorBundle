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
    protected $direction = null;
    protected $last_position_e = null;
    protected $first_position_e = null;
    protected $obj_id = null;

    const MOVE_UP = 'up';
    const MOVE_DOWN = 'down';
    const MOVE_TOP = 'top';
    const MOVE_BOTTOM = 'bottom';

    public function getPosition($object)
    {
        switch ($this->getDirection()) {
            case self::MOVE_UP :
                if ($object->getPosition() > $this->getFirstPositionE()) {
                    $new_position = $object->getPosition() - 1;
                }
                break;

            case self::MOVE_DOWN:
                if ($object->getPosition() < $this->getLastPositionE()) {
                    $new_position = $object->getPosition() + 1;
                }
                break;

            case self::MOVE_TOP:
                if ($object->getPosition() > $this->getFirstPositionE()) {
                    $new_position = $this->getFirstPositionE() - 1;
                }
                break;

            case self::MOVE_BOTTOM:
                if ($object->getPosition() < $this->getLastPositionE()) {
                    $new_position = $this->getLastPositionE() + 1;
                }
                break;

            default: $new_position = 0;
        }

        return $new_position;

    }

    public function updatePrevElement($old_position, &$prev_obj, $position)
    {
        switch ($this->getDirection())
        {
            case self::MOVE_UP :
            case self::MOVE_DOWN:
                if ($position > $this->getFirstPositionE()
                    || $position < $this->getLastPositionE())
                {
                    $prev_obj ->setPosition($old_position);
                }
                break;
            case self::MOVE_TOP:
                break;
            case self::MOVE_BOTTOM:
                break;
        }

    }

    abstract public function getLastPosition($entity);
    abstract public function getFirstPosition($entity);
    abstract public function getNewPositionElement($ent_class, $position, $old_position);
    abstract public function findAndUpdatePrevElement($position, $ent_class);

    public function getDirection()
    {
        return $this->direction;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }

    public function getLastPositionE()
    {
        return $this->last_position_e;
    }

    public function setLastPositionE($pos)
    {
        $this->last_position_e = $pos;
        return $this;
    }

    public function getFirstPositionE()
    {
        return $this->first_position_e;
    }

    public function setFirstPositionE($pos)
    {
        $this->first_position_e = $pos;
        return $this;
    }
    
    public function getObjId()
    {
        return $this->obj_id;
    }

    public function setObjId($id)
    {
        $this->obj_id = $id;
        return $this;
    }
}
