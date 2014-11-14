<?php
/*
 * This file is part of the pixSortableBehaviorBundle.
 *
 * (c) Nicolas Ricci <nicolas.ricci@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pix\SortableBehaviorBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SortableAdminController extends CRUDController
{
    /**
     * Move element
     *
     * @param integer $id
     * @param string $position
     */
    public function moveAction($id, $position)
    {
        $direction = $position;
        $id     = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        $ent_class = get_class($object);

        $position_service = $this->get('pix_sortable_behavior.position');
        
        $last_position = $position_service->getLastPosition($ent_class);
        $first_position = $position_service->getFirstPosition($ent_class);

        $position_service
            ->setDirection($direction)
            ->setLastPositionE($last_position)
            ->setFirstPositionE($first_position)
            ->setObjId($id)
        ;
        
        $position = $position_service->getPosition($object);
        $old_position = $object->getPosition();
        $new_position = $position_service->getNewPositionElement($ent_class, $position, $old_position);

        $object->setPosition($new_position);
        $this->admin->update($object);

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                'result' => 'ok',
                'objectId' => $this->admin->getNormalizedIdentifier($object)
            ));
        }
        $translator = $this->get('translator');
        $this->get('session')->getFlashBag()->set('sonata_flash_info', $translator->trans('Position updated'));

        return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
    }

}
