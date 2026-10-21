<?php
// SPDX-License-Identifier: GPL-2.0-or-later
// Source: duplicator 1.3.28, ctrls/class.web.services.php (excerpt; elided context marked '...')
// CVE-2020-11738 (fixed). Copyright the duplicator authors.
class DUP_Web_Services
{
	// ...
    public static function duplicator_download()
    {
        $error = false;

        if (!isset($_GET['id']) || !isset($_GET['hash']) || !isset($_GET['file'])) {
            $error = true;
        }

        $packageId = (int) $_GET['id'];
        $hash      = sanitize_text_field($_GET['hash']);
        $file      = sanitize_text_field($_GET['file']);

        if ($error || ($package = DUP_Package::getByID($packageId)) == false) {
            $error = true;
        }

        if ($error || $hash !== $package->Hash) {
            $error = true;
        }

        switch ($file) {
            case 'sql':
                $fileName = "{$package->NameHash}_database.sql";
                break;
            case 'archive':
                $format   = strtolower($package->Archive->Format);
                $fileName = "{$package->NameHash}_archive.{$format}";
                break;
            case 'installer':
                $fileName = $package->NameHash.'_installer.php';
                break;
            default:
                $error    = true;
        }

        $filepath = DUPLICATOR_SSDIR_PATH.'/'.$fileName;

        // Process download
        if (!$error && file_exists($filepath)) {
            // Clean output buffer
            if (ob_get_level() !== 0 && @ob_end_clean() === FALSE) {
                @ob_clean();
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: '.filesize($filepath));
            flush(); // Flush system output buffer

            try {
                $fp = @fopen($filepath, 'r');
                if (false === $fp) {
                    throw new Exception('Fail to open the file '.$filepath);
                }
                while (!feof($fp) && ($data = fread($fp, DUPLICATOR_BUFFER_READ_WRITE_SIZE)) !== FALSE) {
                    echo $data;
                }
                @fclose($fp);
            }
            catch (Exception $e) {
                readfile($filepath);
            }
            exit;
        } else {
            // if the request is wrong wait to avoid brute force attack
            sleep(2);
            wp_die('Invalid request');
        }
    }
}
