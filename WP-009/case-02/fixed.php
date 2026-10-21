<?php
// SPDX-License-Identifier: GPL-3.0-only
// Source: woocommerce-compare-products 3.2.2, classes/class-wc-compare-functions.php (excerpt; elided context marked '...')
// CVE-2024-12313 (fixed). Copyright the woocommerce-compare-products authors.
class Functions 
{
    // ...
	public static function get_compare_list() {
		$current_compare_list = isset($_COOKIE['woo_compare_list'])
			? json_decode($_COOKIE['woo_compare_list'], true)
			: array();
		
		$return_compare_list = array();
		if (is_array($current_compare_list) && count($current_compare_list) > 0) {
			foreach ($current_compare_list as $product_id) {
				if (self::check_product_activate_compare($product_id)) {
					$return_compare_list[] = (int)$product_id;
				}
			}
		}
		return $return_compare_list;
	}
}
