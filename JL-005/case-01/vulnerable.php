<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// SYNTHETIC repro of CVE-2026-57832 (EDocman, JoomDonation) — unauthenticated blind SQLi
// in the document listing. The exact parameter is not public; this uses a representative
// category filter read from request input. No verbatim vendor source is public.
defined('_JEXEC') or die;

class EDocmanModelDocuments
{
    public function getDocuments()
    {
        $db    = Factory::getDbo();
        $input = Factory::getApplication()->input;

        $catid = $input->get('catid');   // representative frontend filter (tainted)
        $db->setQuery(
            "SELECT * FROM #__edocman_documents WHERE published = 1 AND catid = '" . $catid . "'"
        );
        return $db->loadObjectList();
    }
}
