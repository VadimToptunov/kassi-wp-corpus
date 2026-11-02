<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Fixed form of CVE-2026-65766: an ORDER BY column cannot be quoted, so the request value is
// mapped through an allow-list — the SQL only ever contains a constant from $allowed.
defined('_JEXEC') or die;

class SppagebuilderModelDynamic
{
    public function getItems()
    {
        $db    = Factory::getDbo();
        $input = Factory::getApplication()->input;

        $allowed = array('latest' => 'created DESC', 'title' => 'title ASC', 'ordering' => 'ordering ASC');
        $key     = $input->getCmd('order');
        $order   = isset($allowed[$key]) ? $allowed[$key] : 'created DESC';
        $db->setQuery(
            "SELECT * FROM #__sppagebuilder WHERE published = 1 ORDER BY " . $order
        );
        return $db->loadObjectList();
    }
}
