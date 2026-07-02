<?php
/**
 * 用户注册、登录等于用户资料相关的处理流程
 */

namespace WenpriseSpaceName\Controllers;

use WenpriseSpaceName\Helpers;

/**
 * 账号页控制器
 *
 * 负责演示 Router 驱动页面的最小控制器写法。
 */
class AccountController
{
    /**
     * 渲染账号页
     *
     * @return string
     */
    public function index()
    {
        return Helpers::render_view('account.index');
    }
}
