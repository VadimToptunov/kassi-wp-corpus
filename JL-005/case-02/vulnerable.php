<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// SYNTHETIC repro of CVE-2026-49048 (JoomCCK, JoomCoder) — unauthenticated SQLi.
// Verified cause (public advisory): a front-end controller task concatenates a request
// parameter read via Joomla's STRING filter ($input->getString), which does NOT escape
// single quotes, into a $db->setQuery() statement. No verbatim vendor source is public.
defined('_JEXEC') or die;

class JcckStoreModel
{
    public function getFormEntries()
    {
        $db    = Factory::getDbo();
        $input = Factory::getApplication()->input;

        $field = $input->getString('field');   // STRING filter -> quotes NOT escaped (tainted)
        $db->setQuery(
            "SELECT * FROM #__jcck_store_form_entries WHERE field_name = '" . $field . "'"
        );
        return $db->loadObjectList();
    }
}
