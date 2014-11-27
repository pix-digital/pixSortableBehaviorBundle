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

use Doctrine\ODM\MongoDB\DocumentManager;

class PositionODMHandler extends PositionHandler
{

    /**
     *
     * @var DocumentManager
     */
    protected $dm;

    public function __construct(DocumentManager $documentManager)
    {
        $this->dm = $documentManager;
    }

    public function getLastPosition($entity)
    {
        $result = $this->dm
            ->createQueryBuilder($entity)
            ->hydrate(false)
            ->select('position')
            ->sort('position','desc')
            ->limit(1)
            ->getQuery()
            ->getSingleResult()
        ;

        if (is_array($result) && isset($result['position'])) {
            return $result['position'];
        }
        
        return 0;
    }

    public function getFirstPosition($entity)
    {
        $result = $this->dm
            ->createQueryBuilder($entity)
            ->hydrate(false)
            ->select('position')
            ->sort('position','asc')
            ->limit(1)
            ->getQuery()
            ->getSingleResult()
        ;

        if (is_array($result) && isset($result['position'])) {
            return $result['position'];
        }

        return 0;
    }

    public function getNewPositionElement($ent_class, $position, $old_position)
    {
        $repo = $this->dm->getRepository($ent_class);

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
            $this->dm->persist($prev_obj);
            $this->dm->flush();
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

            $prev_obj = $this->dm
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
