<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Fixed form of CVE-2026-57832: the category id is cast to an integer with $input->getInt().
defined('_JEXEC') or die;

class EDocmanModelDocuments
{
    public function getDocuments()
    {
        $db    = Factory::getDbo();
        $input = Factory::getApplication()->input;

        $catid = $input->getInt('catid');   // integer cast -> SQL-safe
        $db->setQuery(
            "SELECT * FROM #__edocman_documents WHERE published = 1 AND catid = " . $catid
        );
        return $db->loadObjectList();
    }
}
