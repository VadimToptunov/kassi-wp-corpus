<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// SYNTHETIC repro of CVE-2026-74804 (YOOtheme Zoo) — unauthenticated SQLi in
// ItemController::element(). Verified: the `filter_type` / `type_filter` request
// parameters reach the query unsanitized. No verbatim vendor source is public.
defined('_JEXEC') or die;

class ItemController
{
    public function element()
    {
        $db    = Factory::getDbo();
        $input = Factory::getApplication()->input;

        $type = $input->get('type_filter');   // bare JInput ->get on the input object (tainted)
        $db->setQuery(
            "SELECT * FROM #__zoo_item WHERE type = '" . $type . "' AND state = 1"
        );
        return $db->loadObjectList();
    }
}
