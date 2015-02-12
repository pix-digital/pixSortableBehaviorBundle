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
        $positionFiles = $this->getPositionFieldByEntity($entity);
        $result = $this->dm
            ->createQueryBuilder($entity)
            ->hydrate(false)
            ->select($positionFiles)
            ->sort($positionFiles,'desc')
            ->limit(1)
            ->getQuery()
            ->getSingleResult()
        ;

        if (is_array($result) && isset($result[$positionFiles])) {
            return $result[$positionFiles];
        }
        
        return 0;
    }


}
