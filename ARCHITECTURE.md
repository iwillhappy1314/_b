# 插件开发模板架构说明

## 1. 文档目标

本文档用于说明当前 WordPress 插件开发模板的推荐架构，以及基于该模板开发新插件时建议遵循的分层方式。

本模板虽然引入了 `wenprise/mvc`、`wenprise/eloquent` 和 `wenprise/forms`，但推荐的前台页面实现方式并不是把所有内容都塞进传统 Controller，而是采用更贴近 WordPress 插件开发的组合式结构：

- `Init` 负责统一启动和注册。
- `Integrate` 负责 WordPress / WooCommerce Hook 接入。
- `Shortcodes` 负责独立前台页面入口。
- `Services` 负责业务逻辑、页面基础设施、展示数据组装。
- `templates` 负责最终页面输出。
- `Controllers` 只用于少量 Router 驱动页面。

## 2. 模板当前的运行入口

模板启动入口在 [src/Init.php](/Volumes/Storage/Documents/GitHub/_b/src/Init.php:15)。

`Init` 的职责应保持为：

1. 注册后台页、前台入口、集成类和短代码类。
2. 注册 REST API 控制器。
3. 初始化 MVC 容器、视图路径和服务提供者。
4. 注册少量自定义路由。

`Init` 应当是启动入口，不应继续膨胀为业务逻辑中心。

## 3. 推荐目录职责

### 3.1 `Actions`

负责插件生命周期动作，例如激活、停用、初始化默认页面或默认配置。

### 3.2 `AdminPages`

负责 WordPress 后台页面、后台表单处理和管理入口。

### 3.3 `Api`

负责 REST API 请求入口、参数校验、权限校验和 JSON 响应。

### 3.4 `Controllers`

负责少量 Router 驱动的前台页面请求。

适合放：

- 登录、注册、找回密码等步骤页
- 不适合用 Shortcode 承载的路由页

不适合放：

- 全部前台页面
- 大量业务规则

### 3.5 `Integrate`

负责与 WordPress、WooCommerce 或其他插件接线。

推荐职责：

- 注册 Hook
- 处理 `admin_post`
- 接收 WooCommerce 订单、购物车、用户资料等回调
- 调用业务 Service
- 负责 notice、redirect、桥接外部系统

一句话原则：

`Integrate` 是接线层，不是总控层。

### 3.6 `Models`

负责 Eloquent 模型、自定义数据表、关系和查询作用域。

### 3.7 `Services`

这是模板中后续业务实现最推荐承载的目录。

建议把 `Services` 理解为 3 类角色：

1. 领域服务  
负责积分、订单同步、等级计算、第三方接口等业务规则。

2. 页面基础设施服务  
通常命名为 `*PageService`，负责页面 URL、页面 ID、页面存在性等基础能力。

3. 页面展示组装服务  
通常命名为 `*PageViewService` 或 `*ViewService`，负责把业务服务结果组装成模板需要的数据结构。

### 3.8 `Shortcodes`

负责独立前台页面入口。

推荐职责：

- 注册 shortcode
- 判断登录态或访客态
- 调用页面展示组装服务
- 调用模板输出

不推荐职责：

- 在 `render()` 里堆大量业务计算
- 直接承担复杂数据查询与规则处理

### 3.9 `templates`

只负责展示。

模板中允许：

- 固定标题和按钮文案
- 简单条件判断
- 简单循环
- Tailwind-first 的布局与样式类

模板中不应放：

- 复杂业务计算
- 大量数据库查询
- 权限主逻辑

## 4. 推荐页面架构

### 4.1 无表单页面

推荐调用链：

`PageService -> PageViewService -> Shortcode -> template`

### 4.2 带提交动作的页面

推荐调用链：

`Integrate -> Domain Service -> redirect / notice -> Shortcode -> template`

### 4.3 每层职责

#### `PageService`

只负责页面基础设施：

- 获取页面 URL
- 获取页面 ID
- 激活时注册页面

#### `PageViewService`

只负责展示数据组装：

- header 数据
- hero 数据
- form 默认值
- section 列表
- notice 展示数据

#### `Shortcode`

只负责入口和渲染：

- 登录态判断
- 访客态 fallback
- 调用 `PageViewService`
- 渲染模板

#### `Integrate`

只负责接线：

- 注册 Hook
- 接收提交
- 调用领域 Service
- redirect 回页面

#### `template`

只负责输出 HTML 与固定文案。

## 5. 页面数据约定

模板中推荐每个前台页面尽量只向模板传一个顶层变量，统一命名为 `view_model` 或 `page_view_model`。

推荐结构：

```php
[
    'header' => [],
    'hero' => [],
    'notice' => [],
    'form' => [],
    'sections' => [],
]
```

不要在不同页面里随意混用：

- `view_data`
- `view_model`
- 多个平铺变量

模板生成的新插件应尽量从一开始就统一约定。

## 6. 哪些内容留在后端，哪些直接写模板

### 6.1 应留在后端的内容

- 用户状态
- 权限状态
- notice 数据
- 已格式化金额、日期、状态
- 枚举 label 映射
- 业务规则计算结果

### 6.2 适合直接写在模板的内容

- 单页固定标题
- 固定按钮文案
- 固定帮助文案
- 页面专用静态说明文字
- 明显固定的表单说明

## 7. 模板默认开发规则

基于本模板开发新插件时，建议遵循以下规则：

1. 新增前台页面时，优先走 `Shortcode + Service + template`。
2. 只有少量需要自定义路由的页面才使用 `Controller`。
3. 表单处理优先走 `Integrate + Service`，不要把提交处理写进模板。
4. 新增业务逻辑优先写进 `Services`，不要继续堆在 `Shortcode` 或 `Integrate`。
5. 固定文案优先写模板，动态业务态数据留在后端。
6. 优先增量演进，不做模板级过度抽象。

## 8. 新增页面推荐步骤

1. 新建 `src/Services/*PageService.php`
2. 新建 `src/Services/*PageViewService.php`
3. 新建 `src/Shortcodes/*Shortcode.php`
4. 新建 `templates/*.php`
5. 如有表单提交，再新建 `src/Integrate/*Integrate.php`
6. 在 [src/Init.php](/Volumes/Storage/Documents/GitHub/_b/src/Init.php:44) 中注册

## 9. 模板后续演进方向

如果以后要继续升级这个模板，建议优先做下面几件事：

1. 让更多模板示例类支持构造函数注入。
2. 增加一个页面级 `PageService + PageViewService` 示例。
3. 统一更多默认命名和文档说明。
4. 保持模板代码和文档同步更新。
