<?php
// Synthetic reflected-XSS fixture for CVE-2026-63264 (JoomShopping, product frontend controller).
// A crafted request value is echoed back by the product controller without escaping.
defined('_JEXEC') or die;

class JoomShoppingControllerProduct
{
    public function display($app)
    {
        $keyword = $app->input->getRaw('keyword');
        echo '<h2 class="product-search">Results for "' . $keyword . '"</h2>';
    }
}
