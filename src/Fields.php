<?php

namespace WenpriseSpaceName;

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Fields
{

    public function __construct()
    {
        add_action('carbon_fields_register_fields', [$this, 'register_fields']);
        add_action('after_setup_theme', [$this, 'load_fields']);

    }


    function register_fields()
    {
        Container::make('user_meta', '附加信息')
                 ->add_fields([
                     Field::make('text', 'user_no', '使用者编号'),
                     Field::make('select', 'user_department', '使用者单位'),
                 ]);

        if (current_user_can('manage_options')) {
            Container::make('user_meta', '用户管理')
                     ->add_fields([
                         Field::make('text', 'admin_note', '备注'),
                         Field::make('date_time', 'due_date', '使用期限'),
                     ]);
        }
    }


    function load_fields()
    {
        Carbon_Fields::boot();
    }
}