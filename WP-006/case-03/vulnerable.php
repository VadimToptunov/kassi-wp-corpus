<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: ap-custom-testimonial 1.4.7, inc/model/apct_current_testimonial.php (excerpt; elided context marked '...')
// CVE-2022-23912. Copyright the ap-custom-testimonial authors.
?>
                              <?php global $wpdb; 
                               $table_name = $wpdb->prefix . "apct_testimonial_detail";
                               $testimonial_detail = $wpdb->get_results("SELECT * FROM $table_name WHERE id=".$_GET['id']);
                               foreach ($testimonial_detail as $row){
                                $id = $_GET['id'];
                                $testim_description = unserialize($row->author_details);
                                } ?>
                               <form method="post" action="<?php echo admin_url() . 'admin-post.php' ?>" enctype="multipart/form-data" class="apct-testimonial-form">
                                        <input type="hidden" name="action" value="apct_current_testimonial_options" />
                                        <input type="hidden" name="current_post_id" value="<?php echo $_GET['id'];?>" />
