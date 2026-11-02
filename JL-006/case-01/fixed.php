<?php
// Fixed: the reflected term is escaped with htmlspecialchars() before output (CVE-2026-65762).
defined('_JEXEC') or die;

class PhocaGuestbookViewList
{
    public function renderSearch($app)
    {
        $search = $app->input->getRaw('search');
        echo '<div class="phoca-gb-search">You searched for: '
            . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '</div>';
    }
}
