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


    public function getPosition($object, $position, $last_position)
    {
        switch ($position) {
            case 'up' :
                if ($object->getPosition() > 0) {
                    $new_position = $object->getPosition() - 1;
                }
                break;

            case 'down':
                if ($object->getPosition() < $last_position) {
                    $new_position = $object->getPosition() + 1;
                }
                break;

            case 'top':
                if ($object->getPosition() > 0) {
                    $new_position = 0;
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

    abstract public function getLastPosition($entity);

}
