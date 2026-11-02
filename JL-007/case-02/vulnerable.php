<?php
// Synthetic arbitrary-file-read fixture for CVE-2026-60027 (Quix Page Builder Pro, unauth file read).
// A request-controlled template path is read and returned, allowing ../ traversal to any file.
defined('_JEXEC') or die;

class QuixModelTemplate
{
    public function load($app)
    {
        $tpl = $app->input->getString('template');
        return file_get_contents(JPATH_ROOT . '/templates/' . $tpl . '.json');
    }
}
