<?php
// Fixed: the template name is stripped to a bare filename with basename(), so no ../ survives into
// the file read (CVE-2026-60027).
defined('_JEXEC') or die;

class QuixModelTemplate
{
    public function load($app)
    {
        $tpl = basename($app->input->getString('template'));
        return file_get_contents(JPATH_ROOT . '/templates/' . $tpl . '.json');
    }
}
