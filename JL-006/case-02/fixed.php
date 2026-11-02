<?php
// Fixed: the keyword is escaped via the view's escape() helper before output (CVE-2026-63264).
defined('_JEXEC') or die;

class JoomShoppingControllerProduct
{
    public function display($app, $view)
    {
        $keyword = $app->input->getRaw('keyword');
        echo '<h2 class="product-search">Results for "' . $view->escape($keyword) . '"</h2>';
    }
}
