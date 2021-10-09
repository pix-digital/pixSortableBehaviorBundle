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

use Doctrine\Common\Util\ClassUtils;
use Pix\SortableBehaviorBundle\Services\PositionHandler;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @package Pix\SortableBehaviorBundle
 */
class SortableAdminController extends CRUDController
{
    /**
     * Move element
     *
     * @param string $position
     *
     * @return RedirectResponse|Response
     */
    public function moveAction(
        $position,
        Request $request,
        PositionHandler $positionHandler
    ): Response
    {
        $translator = $this->get('translator');

        if (!$this->admin->isGranted('EDIT')) {
            $this->addFlash(
                'sonata_flash_error',
                $translator->trans('flash_error_no_rights_update_position')
            );

            return new RedirectResponse($this->admin->generateUrl(
                'list',
                array('filter' => $this->admin->getFilterParameters())
            ));
        }

        $object          = $this->admin->getSubject();

        $lastPositionNumber = $positionHandler->getLastPosition($object);
        $newPositionNumber  = $positionHandler->getPosition($object, $position, $lastPositionNumber);

        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($object, $positionHandler->getPositionFieldByEntity($object), $newPositionNumber);

        $this->admin->update($object);

        if ($this->isXmlHttpRequest($request)) {
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
