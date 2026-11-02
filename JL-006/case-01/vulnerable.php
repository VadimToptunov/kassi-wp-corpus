<?php
// Synthetic reflected-XSS fixture for CVE-2026-65762 (Phoca Guestbook, front-end guestbook).
// The front-end reflects an unfiltered search term straight into the page.
defined('_JEXEC') or die;

class PhocaGuestbookViewList
{
    public function renderSearch($app)
    {
        $search = $app->input->getRaw('search');
        echo '<div class="phoca-gb-search">You searched for: ' . $search . '</div>';
    }
}
