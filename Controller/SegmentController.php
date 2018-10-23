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

use Mautic\CoreBundle\Controller\CommonController;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SegmentController extends CommonController
{
    public function batchExportAction($segmentId = null)
    {
        if (!$segmentId) {
            return $this->notFound('mautic.segmentextras.export.notfound');
        }
        $contactIds = $this->getSegmentLeadIdsForExport($segmentId);

        // adjust $size for memory vs. speed
        $batches = array_chunk($contactIds, 100);

        //testing
        /** @var OverrideLeadRepository $contactRepo */
        $contactRepo = $this->getModel('lead')->getRepository();

        $fileName = sprintf('ContactsExportFromSegment%s.csv', str_replace(' ', '', $segmentId));

        $response = new StreamedResponse(
            function () use ($contactRepo, $batches) {
                ini_set('max_execution_time', 0);
                $handle = fopen('php://output', 'w');

                $fieldNames = [];
                foreach ($batches as $batch) {
                    $leads = $contactRepo->getEntities(
                        ['ids' => $batch, '', 'withTotalCounts' => 0, 'withChannelRules' => 1, 'ignore_paginator' => 1]
                    );
                    /**
                     * @var int
                     * @var Lead $lead
                     */
                    foreach ($leads as $id => $lead) {
                        if (empty($fieldNames)) {
                            $fields      = $lead->getFields(true);
                            $columnNames = array_map(
                                function ($f) {
                                    return $f['label'];
                                },
                                $fields
                            );
                            $columnNames = array_merge(['Id'], $columnNames);
                            fputcsv($handle, $columnNames);
                            $fieldNames = array_map(
                                function ($f) {
                                    return $f['alias'];
                                },
                                $fields
                            );
                        }
                        $values = [$id];
                        foreach ($fieldNames as $fieldName) {
                            $values[] = $lead->getFieldValue($fieldName);
                        }
                        fputcsv($handle, $values);
                    }
                    $contactRepo->clear();
                }
                fclose($handle);
            },
            200,
            [
                'Content-Type'        => 'application/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
            ]
        );

        return $response;
    }

    public function getSegmentLeadIdsForExport($segmentId)
    {
        /** @var QueryBuilder $q */
        $q = $this->get('doctrine.orm.entity_manager')->getConnection()->createQueryBuilder();
        $q->select('lll.lead_id')
            ->from('lead_lists_leads', 'lll')
            ->where(
                $q->expr()->eq('lll.manually_removed', ':false'),
                $q->expr()->eq('lll.leadlist_id', ':segmentId')
            )
            ->setParameter('false', false, 'boolean')
            ->setParameter('segmentId', $segmentId);

        $result = $q->execute()->fetchAll();
        if (!empty($result)) {
            $contactIds = array_column($result, 'lead_id');
        }

        return $contactIds;
    }
}
