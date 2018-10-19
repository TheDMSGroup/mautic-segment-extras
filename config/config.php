<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'name'        => 'Segment Extras',
    'description' => 'Extends Mautic Lead Bundle\'s Lead List (Segment) functionality,',
    'version'     => '1.0',
    'author'      => 'Mautic',
    'routes'      => [
        'main' => [
            'mautic_segment_extras_batch_export' => [
                'path'       => '/segment/export/{segmentId}',
                'controller' => 'MauticSegmentExtrasBundle:Segment:batchExport',
                'method'     => 'GET',
            ],
        ],
    ],
    'services'    => [
    ],
];
