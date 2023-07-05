# wp-db-base

WordPress database custom tables register abstract

## Usage

````php
class OpenAuth extends WPDBase\Database
{

    /**
     * 定义主题路径命名空间
     */
    public function setTables()
    {

        return [

            // 用户每日成就
            "CREATE TABLE `{$this->wpdb->prefix}open_auths` (
			id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            provider varchar(200) DEFAULT NULL,
            open_id varchar(200) DEFAULT NULL,
            union_id varchar(200) DEFAULT NULL,
            access_token varchar(200) DEFAULT NULL,
            refresh_token varchar(200) DEFAULT NULL,
            nickname varchar(20) DEFAULT NULL,
            avatar varchar(200) DEFAULT NULL,
            province varchar(20) DEFAULT NULL,
            city varchar(20) DEFAULT NULL,
            gender varchar(20) DEFAULT NULL,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            deleted_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
		) $this->collate;",

        ];
    }
}

new OpenAuth();
````