<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticSegmentExtrasBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

/**
 * Class SegmentExtrasIntegration.
 *
 * This plugin does not add integrations. This is here purely for name/logo/etc.
 */
class SegmentExtrasIntegration extends AbstractIntegration
{
    /**
     * @return string
     */
    public function getAuthenticationType()
    {
        return 'none';
    }

    /**
     * @return array
     */
    public function getSupportedFeatures()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'SegmentExtras';
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return 'Segment Extras';
    }

}
