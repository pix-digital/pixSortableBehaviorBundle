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

use Doctrine\ORM\EntityManager;

class PositionORMHandler extends PositionHandler
{

    /**
     *
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getLastPosition($entity)
    {

        $query = $this->em->createQuery('SELECT MAX(m.position) FROM '.$entity.' m');
        $result = $query->getResult();

        if (array_key_exists(0, $result)) {
            return intval($result[0][1]);
        }

        return 0;
    }

     public function getFirstPosition($entity)
    {

        $query = $this->em->createQuery('SELECT MIN(m.position) FROM '.$entity.' m');
        $result = $query->getResult();

        if (array_key_exists(0, $result)) {
            return intval($result[0][1]);
        }

        return 0;
    }

    public function getNewPositionElement($ent_class, $position, $old_position)
    {
        $repo = $this->em->getRepository($ent_class);

        //find object with new position value
        $prev_obj = $repo->findOneBy(['position' => $position]);
        $new_position = $position;
        if(!is_null($prev_obj))
        {
            $this->updatePrevElement($old_position, $prev_obj, $position);
        }
        else
        {
            list($prev_obj, $new_position) = $this->findAndUpdatePrevElement($position, $ent_class);
        }

        if($prev_obj)
        {
            $this->em->persist($prev_obj);
            $this->em->flush();
        }

        return $new_position;
    }

    public function findAndUpdatePrevElement($position, $ent_class)
    {
        $sign = false;
        $sort = false;
        switch ($this->getDirection())
        {
            case self::MOVE_UP :
                if ($position > $this->getFirstPositionE())
                {
                    $sign = '<';
                    $sort = 'DESC';
                }
            break;
            case self::MOVE_DOWN:
                if ($position < $this->getLastPositionE())
                {
                    $sign = '>';
                    $sort = 'ASC';
                }
                break;
            case self::MOVE_TOP:
                break;
            case self::MOVE_BOTTOM:
                break;
        }

        if($sign)
        {
            $meta = $this->em->getClassMetadata($ent_class);
            $identifier = $meta->getSingleIdentifierFieldName();

            $prev_obj = $this->em
                ->createQuery('select
                                    e
                                from ' . $ent_class .' e
                                where
                                    e.position ' . $sign . ' :p
                                    and e.' . $identifier . ' <> :id
                                order by
                                    e.position ' . $sort)
                ->setParameter('p', $position)
                ->setParameter('id', $this->getObjId())
                ->setFirstResult(0)
                ->setMaxResults(1)
                ->getResult()
            ;

            if(!empty($prev_obj))
            {
                $old_position = $prev_obj[0]->getPosition();
                $this->updatePrevElement($position, $prev_obj[0], $old_position);
                return [$prev_obj[0], $old_position];
            }
        }

        return [null, $position];
    }
}
