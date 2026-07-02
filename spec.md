# WordPress 插件开发模板说明

## 技术栈

1. 使用 `wenprise/mvc` 提供基础容器、视图和路由能力。
2. 使用 `wenprise/eloquent` 提供模型与数据库访问能力。
3. 使用 `wenprise/forms` 简化后台或表单开发。
4. 使用 `wenprise/templates-helper` 处理模板加载。

## 推荐目录结构

| 目录/文件名 | 职责描述 |
|:--|:--|
| `Actions` | 处理插件激活、停用、卸载、默认页面初始化等生命周期动作 |
| `AdminPages` | 后台管理页面、后台表单处理与配置入口 |
| `Api` | REST API 控制器 |
| `Controllers` | 少量 Router 驱动页面入口 |
| `Databases` | 自定义数据表结构定义 |
| `Integrate` | WordPress / WooCommerce / 第三方插件接线层 |
| `ListTables` | `WP_List_Table` 后台列表 |
| `Metaboxes` | 编辑器 Metabox |
| `Middleware` | 路由中间件 |
| `Models` | Eloquent 模型与关系 |
| `Providers` | 服务提供者 |
| `Services` | 业务逻辑、页面基础设施、展示数据组装 |
| `Shortcodes` | 独立前台页面入口 |
| `Init.php` | 插件主启动入口 |
| `Frontend.php` | 前端资源加载与前端钩子 |
| `Helpers.php` | 全局辅助方法 |
| `templates/` | 最终展示模板 |

## 推荐开发模式

### 前台独立页面

优先使用：

`PageService -> PageViewService -> Shortcode -> template`

### 带表单提交的页面

优先使用：

`Integrate -> Domain Service -> redirect / notice -> Shortcode -> template`

### 少量自定义路由页

仅在确实需要 Router 时，使用：

`Controller -> template`

## 关键约定

1. `Integrate` 负责接线，不负责承载全部业务。
2. `Shortcode` 负责入口和渲染，不负责复杂规则。
3. `Services` 是主要业务承载层。
4. `templates` 只负责展示。
5. 固定文案直接写在模板，动态业务态数据留在后端。

## 进一步说明

详细架构约定请查看 [ARCHITECTURE.md](/Volumes/Storage/Documents/GitHub/_b/ARCHITECTURE.md)。
