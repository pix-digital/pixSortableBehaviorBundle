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


    public function getPosition($object, $position, $last_position, $first_position)
    {
        switch ($position) {
            case 'up' :
                if ($object->getPosition() > $first_position) {
                    $new_position = $object->getPosition() - 1;
                }
                break;

            case 'down':
                if ($object->getPosition() < $last_position) {
                    $new_position = $object->getPosition() + 1;
                }
                break;

            case 'top':
                if ($object->getPosition() > $first_position) {
                    $new_position = $first_position;
                }
                break;

            case 'bottom':
                if ($object->getPosition() < $last_position) {
                    $new_position = $last_position;
                }
                break;

            default: $new_position = 0;
        }

        return $new_position;

    }

    public function updatePrevElement($direction, $position, $old_position, &$prev_obj, $last_position, $first_position)
    {
        switch ($direction)
        {
            case 'up' :
            case 'down':
                if ($position > $first_position || $position < $last_position)
                {
                    $prev_obj ->setPosition($old_position);
                }
                break;
            case 'top':
                $prev_obj ->setPosition(($first_position - 1));
                break;
            case 'bottom':
                $prev_obj ->setPosition(($last_position + 1));
                break;
        }

    }

    /**
     *
     * @param type $direction
     * @param type $position
     * @param \Doctrine\ORM\EntityManager $em
     * @param type $last_position
     * @param type $ent_class
     */
    public function findAndUpdatePrevElement($direction, $position, &$em, $last_position, $ent_class, $id, $first_position)
    {
        $sign = false;
        $sort = false;
        switch ($direction)
        {
            case 'up' :
                if ($position > $first_position)
                {
                    $sign = '<';
                    $sort = 'DESC';
                }
            break;  
            case 'down':
                if ($position < $last_position)
                {
                    $sign = '>';
                    $sort = 'ASC';
                }
                break;
            case 'top':
                break;
            case 'bottom':
                break;
        }

        if($sign)
        {
            $meta = $em->getClassMetadata($ent_class);
            $identifier = $meta->getSingleIdentifierFieldName();

            $prev_obj = $em
                ->createQuery('select
                                    e
                                from ' . $ent_class .' e
                                where
                                    e.position ' . $sign . ' :p
                                    and e.' . $identifier . ' <> :id
                                order by
                                    e.position ' . $sort)
                ->setParameter('p', $position)
                ->setParameter('id', $id)
                ->setFirstResult(0)
                ->setMaxResults(1)
                ->getResult()
            ;

            if(!empty($prev_obj))
            {
                $old_position = $prev_obj[0]->getPosition();
                $this->updatePrevElement($direction, $old_position, $position, $prev_obj[0], $last_position, $first_position);
                return [$prev_obj[0], $old_position];
            }
        }

        return false;
    }

    abstract public function getLastPosition($entity);

}
