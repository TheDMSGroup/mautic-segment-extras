<?php

/*
 * @copyright   2018 Mautic Contributors. All rights reserved
 * @author      Digital Media Solutions, LLC
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\MauticSegmentExtrasBundle;

use Mautic\PluginBundle\Bundle\PluginBundleBase;

class MauticSegmentExtrasBundle extends PluginBundleBase
{
    public function getParent()
    {
        return 'MauticLeadBundle';
    }
}
