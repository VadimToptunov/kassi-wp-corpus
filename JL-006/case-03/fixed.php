<?php
// Fixed: the dc_filter value is escaped with htmlspecialchars() for both the attribute and the text
// node before output (CVE-2026-102426).
defined('_JEXEC') or die;

class SppagebuilderAddonDynamicContentFilter
{
    public function render($app, $fieldId)
    {
        $value = $app->input->getRaw('dc_filter_' . $fieldId);
        $safe  = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        echo '<span class="dc-active-filter" data-field="' . $safe . '">' . $safe . '</span>';
    }
}
