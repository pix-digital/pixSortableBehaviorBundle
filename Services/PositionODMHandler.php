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
use Doctrine\ODM\MongoDB\DocumentManager;

class PositionODMHandler extends PositionHandler
{
    /**
     * DocumentManager
     */
    protected $dm;

    public function __construct(DocumentManager $documentManager)
    {
        $this->dm = $documentManager;
    }

    public function getLastPosition($entity): int
    {
        $entityClass = ClassUtils::getClass($entity);
        $parentEntityClass = true;
        while ($parentEntityClass)
        {
            $parentEntityClass = ClassUtils::getParentClass($entityClass);
            if ($parentEntityClass) {
                $reflection = new \ReflectionClass($parentEntityClass);
                if($reflection->isAbstract()) {
                    break;
                }
                $entityClass = $parentEntityClass;
            }
        }

        $positionFields = $this->getPositionFieldByEntity($entityClass);
        $result = $this->dm
            ->createQueryBuilder($entityClass)
            ->hydrate(false)
            ->select($positionFields)
            ->sort($positionFields, 'desc')
            ->limit(1)
            ->getQuery()
            ->getSingleResult();

        if (is_array($result) && isset($result[$positionFields])) {
            return $result[$positionFields];
        }

        return 0;
    }
}
