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

use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SortableAdminController extends CRUDController
{
    /**
     * Move element
     *
     * @param string $position
     */
    public function moveAction($position)
    {
        $translator = $this->get('translator');

        if (!$this->admin->isGranted('EDIT')) {
            $this->addFlash(
                'sonata_flash_error',
                $translator->trans('flash_error_no_rights_update_position')
            );

            return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
        }

        $object = $this->admin->getSubject();

        /** @var PositionHandler $positionService */
        $positionService = $this->get('pix_sortable_behavior.position');

        $entity = \Doctrine\Common\Util\ClassUtils::getClass($object);

        $lastPosition = $positionService->getLastPosition($entity);

        $position = $positionService->getPosition($object, $position, $lastPosition);

        $setter = sprintf('set%s', ucfirst($positionService->getPositionFieldByEntity($entity)));

        if (!method_exists($object, $setter)) {
            throw new \LogicException(
                sprintf(
                    '%s does not implement ->%s() to set the desired position.',
                    $object,
                    $setter
                )
            );
        }

        $object->{$setter}($position);
        $this->admin->update($object);

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                'result' => 'ok',
                'objectId' => $this->admin->getNormalizedIdentifier($object)
            ));
        }

        $this->addFlash(
            'sonata_flash_success',
            $translator->trans('flash_success_position_updated')
        );

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            array('filter' => $this->admin->getFilterParameters())
        ));
    }

}
