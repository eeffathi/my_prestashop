<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licensed under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the license agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    MyPrestaModules
 * @copyright 2013-2020 MyPrestaModules
 * @license LICENSE.txt
 */

require_once _PS_MODULE_DIR_ . 'ordersexport/libraries/phpseclib/Net/SFTP.php';

class MpmOEFTPManager
{
    private $protocol;
    private $port;
    private $server;
    private $user;
    private $password;
    private $path;
    private $passive_mode;
    private $file_transfer_mode;

    private $module_instance;

    public function __construct($configuration)
    {
        $this->module_instance = Module::getInstanceByName('ordersexport');
        $this->protocol = $configuration['ftp_transfer_protocol'];
        $this->port = $this->getPortFromConfig($configuration['ftp_port']);
        $this->server = $configuration['ftp_server'];
        $this->user = $configuration['ftp_user'];
        $this->password = $configuration['ftp_password'];
        $this->path = $this->getSavePathFromConfig($configuration['ftp_folder_path']);
        $this->passive_mode = !empty($configuration['ftp_passive_mode']) ? $configuration['ftp_passive_mode'] : 0;
        $this->file_transfer_mode = !empty($configuration['ftp_file_transfer_mode']) ? $configuration['ftp_file_transfer_mode'] : FTP_ASCII;
    }

    public function copyFileToServer($shop_server_file_path, $file_name)
    {
        if ($this->protocol == 'sftp') {
            $this->copyFileToSFTPServer($shop_server_file_path, $file_name);
        } else {
            $this->copyFileToFTPServer($shop_server_file_path, $file_name);
        }
    }

    private function copyFileToFTPServer($shop_server_file_path, $file_name)
    {
        if ($this->port == '22') {
            throw new \Exception($this->module_instance->l('You chose port 22 which requires SFTP connection!'));
        }

        $connection_id = @ftp_connect($this->server, $this->port);

        if (!$connection_id) {
            throw new \Exception($this->module_instance->l('Can not connect to FTP server!'));
        }

        $is_logged = @ftp_login($connection_id, $this->user, $this->password);

        if (!$is_logged) {
            throw new \Exception($this->module_instance->l('Can not login to FTP account! Make sure that username and password are correct!'));
        }

        if ($this->passive_mode) {
            ftp_pasv($connection_id, true);
        }

        $ftp_server_file_path = $this->path . $file_name;
        $is_saved = ftp_put($connection_id, $ftp_server_file_path, $shop_server_file_path, $this->file_transfer_mode);

        if (!$is_saved) {
            throw new \Exception($this->module_instance->l('Can not save file to FTP server!'));
        }

        return true;
    }

    private function copyFileToSFTPServer($shop_server_file_path, $file_name)
    {
        $sftp = new Net_SFTP($this->server);
        $is_logged = $sftp->login($this->user, $this->password);

        if (!$is_logged) {
            throw new \Exception($this->module_instance->l('Can not login to FTP account! Make sure that username and password are correct!'));
        }

        $ftp_server_file_path = $this->path . $file_name;
        $is_saved = $sftp->put($ftp_server_file_path, $shop_server_file_path, NET_SFTP_LOCAL_FILE);

        if (!$is_saved) {
            throw new \Exception($this->module_instance->l('Can not save file to FTP server!'));
        }

        return true;
    }

    private function getSavePathFromConfig($path_from_configuration)
    {
        $path = '';

        if ($path_from_configuration) {
            $path = $path_from_configuration . '/';
            $path = str_replace('//', '/', $path);
        }

        return $path;
    }

    private function getPortFromConfig($port_from_configuration)
    {
        $default_port = ($this->protocol === 'sftp') ? 22 : 21;
        return !empty($port_from_configuration) ? $port_from_configuration : $default_port;
    }
}