<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Fixed form of CVE-2026-74804: the request value is quoted with $db->quote().
defined('_JEXEC') or die;

class ItemController
{
    public function element()
    {
        $db    = Factory::getDbo();
        $input = Factory::getApplication()->input;

        $type = $input->get('type_filter');
        $db->setQuery(
            "SELECT * FROM #__zoo_item WHERE type = " . $db->quote($type) . " AND state = 1"
        );
        return $db->loadObjectList();
    }
}
