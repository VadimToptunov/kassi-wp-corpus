<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Fixed form of CVE-2026-49048: the request value is passed through $db->quote().
defined('_JEXEC') or die;

class JcckStoreModel
{
    public function getFormEntries()
    {
        $db    = Factory::getDbo();
        $input = Factory::getApplication()->input;

        $field = $input->getString('field');
        $db->setQuery(
            "SELECT * FROM #__jcck_store_form_entries WHERE field_name = " . $db->quote($field)
        );
        return $db->loadObjectList();
    }
}
