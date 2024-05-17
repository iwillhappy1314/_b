<?php

namespace WenpriseSpaceName\Databases;

use WPDBase\Database;

class Member extends Database
{

    /**
     * 定义主题路径命名空间
     */
    public function setTables()
    {

        return [

        //     "CREATE TABLE `{$this->wpdb->prefix}serial_numbers` (
		// 	`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		// 	`user_id` bigint(20),
		// 	`serial_number` text NOT NULL,
		// 	`serial_pass` text NOT NULL,
		// 	`status` varchar(10) DEFAULT NULL,
		// 	PRIMARY KEY (`id`)
		// ) $this->collate;",

        ];
    }
}
