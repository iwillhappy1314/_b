<?php
/**
 * 用户注册、登录等于用户资料相关的处理流程
 */

namespace WenpriseSpaceName\Controllers;

class AccountController
{

    public function index()
    {
        return Helpers::render_view('account.index');
    }

}
