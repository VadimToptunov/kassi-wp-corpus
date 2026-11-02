<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// SYNTHETIC repro of CVE-2026-65766 (SP Page Builder, JoomShaper) — unauthenticated SQLi.
// Verified: the Dynamic Content endpoint takes an `order` request parameter and places it,
// unvalidated, into an ORDER BY clause. No verbatim vendor source is public.
defined('_JEXEC') or die;

class SppagebuilderModelDynamic
{
    public function getItems()
    {
        $db    = Factory::getDbo();
        $input = Factory::getApplication()->input;

        $order = $input->getCmd('order');   // placed straight into ORDER BY (tainted)
        $db->setQuery(
            "SELECT * FROM #__sppagebuilder WHERE published = 1 ORDER BY " . $order
        );
        return $db->loadObjectList();
    }
}
