<?php
// Synthetic local-file-read fixture for CVE-2026-102424 (Balbooa Forms, unauth read during public
// form submission). A guest-controlled upload-field path is read back without sanitisation.
defined('_JEXEC') or die;

class BalboaFormsControllerSubmit
{
    public function readUploadState($app)
    {
        $stored = $app->input->getString('upload_state_file');
        return readfile(JPATH_ROOT . '/tmp/balbooa/' . $stored);
    }
}
