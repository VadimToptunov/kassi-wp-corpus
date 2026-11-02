<?php
// Synthetic reflected-XSS fixture for CVE-2026-102426 (SP Page Builder Pro, Dynamic Content Filter).
// The addon reflects the dc_filter_<fieldId> value into the rendered filter UI without escaping.
defined('_JEXEC') or die;

class SppagebuilderAddonDynamicContentFilter
{
    public function render($app, $fieldId)
    {
        $value = $app->input->getRaw('dc_filter_' . $fieldId);
        echo '<span class="dc-active-filter" data-field="' . $value . '">' . $value . '</span>';
    }
}
