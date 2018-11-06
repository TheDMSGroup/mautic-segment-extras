<?php

/*
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticSegmentExtrasBundle\Controller;

use Mautic\LeadBundle\Controller\ListController as BaseController;
use Mautic\LeadBundle\Entity\LeadListRepository;

class ListController extends BaseController
{
    /**
     * @param     $objectId
     * @param int $page
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function contactsAction($objectId, $page = 1)
    {
        $manuallyRemoved = 0;
        $listFilters     = ['manually_removed' => $manuallyRemoved];
        if ('POST' === $this->request->getMethod() && $this->request->request->has('includeEvents')) {
            $filters = [
                'includeEvents' => InputHelper::clean($this->request->get('includeEvents', [])),
            ];
            $this->get('session')->set('mautic.segment.filters', $filters);
        } else {
            $filters = [];
        }

        if (!empty($filters)) {
            if (isset($filters['includeEvents']) && in_array('manually_added', $filters['includeEvents'])) {
                $listFilters = array_merge($listFilters, ['manually_added' => 1]);
            }
            if (isset($filters['includeEvents']) && in_array('manually_removed', $filters['includeEvents'])) {
                $listFilters = array_merge($listFilters, ['manually_removed' => 1]);
            }
            if (isset($filters['includeEvents']) && in_array('filter_added', $filters['includeEvents'])) {
                $listFilters = array_merge($listFilters, ['manually_added' => 0]);
            }
        }

        // get count first and pass it in. Its better this way. Trust me.
        /** @var LeadListRepository $listRepo */
        $listRepo  = $this->container->get('mautic.lead.repository.lead_list');
        $listCount = $listRepo->getLeadCount([$objectId]);

        return $this->generateContactsGrid(
            $objectId,
            $page,
            'lead:lists:viewother',
            'segment',
            'lead_lists_leads',
            null,
            'leadlist_id',
            $listFilters,
            null,
            null,
            [],
            null,
            'entity.lead_id',
            'DESC',
            $listCount[$objectId]
        );
    }
}
