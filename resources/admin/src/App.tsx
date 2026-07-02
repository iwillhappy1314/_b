import * as Tabs from '@radix-ui/react-tabs';
import * as Separator from '@radix-ui/react-separator';
import { Boxes, LayoutTemplate, Route, Workflow } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { getAdminSettings } from '@/lib/admin-settings';

const architectureChecklist = [
    '后台页面入口优先保持为 Shortcode / AdminPage / Integrate 的清晰组合，而不是把所有逻辑堆进单一类中。',
    '业务规则优先进入 Services，后台表单提交和第三方插件接线优先进入 Integrate。',
    'React 后台页面只负责交互和展示，REST API 与 WordPress 本地化数据负责真实数据入口。',
];

/**
 * 后台应用根组件
 *
 * 提供 React + Radix 后台模板的默认演示界面。
 */
export default function App(): React.JSX.Element {
    const settings = getAdminSettings();

    return (
        <div className="wp-admin-template mx-auto max-w-6xl px-6 py-8">
            <div className="wp-admin-template__shell overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-[0_24px_70px_rgba(15,23,42,0.08)]">
                <div className="wp-admin-template__hero grid gap-8 border-b border-slate-200 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.14),_transparent_38%),linear-gradient(135deg,_#f8fafc_0%,_#eef2ff_55%,_#ffffff_100%)] px-8 py-8 lg:grid-cols-[minmax(0,1.5fr)_minmax(320px,0.9fr)] lg:px-10">
                    <div className="flex flex-col gap-5">
                        <div className="inline-flex w-fit items-center gap-2 rounded-full border border-sky-200 bg-white/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-sky-700">
                            <Workflow className="size-4" />
                            React Admin Template
                        </div>
                        <div className="max-w-3xl">
                            <h1 className="text-3xl font-semibold tracking-tight text-slate-950 lg:text-4xl">
                                使用 React + Radix UI 构建新的 WordPress 插件后台页面
                            </h1>
                            <div className="mt-3 max-w-2xl text-base leading-7 text-slate-600 lg:text-lg">
                                这个模板已经不再依赖 Vue 和 Element UI。默认后台入口改成了 React、TypeScript 和
                                Radix 原语，便于后续扩展表单、列表、筛选器和设置页。
                            </div>
                        </div>
                        <div className="flex flex-wrap items-center gap-3">
                            <Button type="button">
                                开始搭建页面
                            </Button>
                            <Button type="button" variant="secondary">
                                查看模板规范
                            </Button>
                        </div>
                    </div>

                    <div className="grid gap-4 rounded-[28px] border border-white/70 bg-white/80 p-6 shadow-[0_14px_40px_rgba(59,130,246,0.08)] backdrop-blur">
                        <div className="text-sm font-semibold uppercase tracking-[0.12em] text-slate-500">
                            Runtime Settings
                        </div>
                        <div className="grid gap-3">
                            <div className="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <div className="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                                    REST Root
                                </div>
                                <div className="mt-1 break-all text-sm text-slate-700">
                                    {settings.root || '等待 WordPress 注入'}
                                </div>
                            </div>
                            <div className="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                <div className="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">
                                    Nonce
                                </div>
                                <div className="mt-1 break-all text-sm text-slate-700">
                                    {settings.nonce || '等待 WordPress 注入'}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <Tabs.Root defaultValue="overview" className="px-8 py-8 lg:px-10">
                    <Tabs.List
                        className="wp-admin-template-tabs inline-flex flex-wrap gap-2 rounded-full border border-slate-200 bg-slate-50 p-2"
                        aria-label="Admin template sections"
                    >
                        <Tabs.Trigger
                            value="overview"
                            className="wp-admin-template-tabs__trigger inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition-colors data-[state=active]:bg-white data-[state=active]:text-slate-950 data-[state=active]:shadow-sm"
                        >
                            <Boxes className="size-4" />
                            概览
                        </Tabs.Trigger>
                        <Tabs.Trigger
                            value="architecture"
                            className="wp-admin-template-tabs__trigger inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition-colors data-[state=active]:bg-white data-[state=active]:text-slate-950 data-[state=active]:shadow-sm"
                        >
                            <LayoutTemplate className="size-4" />
                            架构
                        </Tabs.Trigger>
                        <Tabs.Trigger
                            value="routing"
                            className="wp-admin-template-tabs__trigger inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-slate-600 transition-colors data-[state=active]:bg-white data-[state=active]:text-slate-950 data-[state=active]:shadow-sm"
                        >
                            <Route className="size-4" />
                            数据入口
                        </Tabs.Trigger>
                    </Tabs.List>

                    <Tabs.Content value="overview" className="mt-8 focus-visible:outline-none">
                        <div className="grid gap-4 lg:grid-cols-3">
                            <article className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.04)]">
                                <div className="text-sm font-semibold uppercase tracking-[0.12em] text-slate-400">UI Stack</div>
                                <div className="mt-3 text-2xl font-semibold text-slate-950">React 18 + Radix UI</div>
                                <div className="mt-3 text-sm leading-6 text-slate-600">
                                    使用 TSX 作为后台页面基础，适合后续继续接入表格、表单和弹窗。
                                </div>
                            </article>
                            <article className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.04)]">
                                <div className="text-sm font-semibold uppercase tracking-[0.12em] text-slate-400">Build Pipeline</div>
                                <div className="mt-3 text-2xl font-semibold text-slate-950">Laravel Mix + TypeScript</div>
                                <div className="mt-3 text-sm leading-6 text-slate-600">
                                    保持模板当前的构建体系，只替换后台入口技术栈，避免引入额外迁移成本。
                                </div>
                            </article>
                            <article className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.04)]">
                                <div className="text-sm font-semibold uppercase tracking-[0.12em] text-slate-400">Styling Rule</div>
                                <div className="mt-3 text-2xl font-semibold text-slate-950">Componentized Tailwind</div>
                                <div className="mt-3 text-sm leading-6 text-slate-600">
                                    组件身份由带前缀类名承载，局部布局继续交给 Tailwind 工具类。
                                </div>
                            </article>
                        </div>
                    </Tabs.Content>

                    <Tabs.Content value="architecture" className="mt-8 focus-visible:outline-none">
                        <div className="grid gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(280px,0.85fr)]">
                            <div className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.04)]">
                                <div className="text-lg font-semibold text-slate-950">推荐后台分层</div>
                                <div className="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                                    {architectureChecklist.map((item) => (
                                        <div key={item} className="flex gap-3 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3">
                                            <div className="mt-1 size-2 rounded-full bg-sky-500" />
                                            <div>{item}</div>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            <div className="rounded-[28px] border border-slate-200 bg-slate-950 p-6 text-white shadow-[0_16px_40px_rgba(15,23,42,0.18)]">
                                <div className="text-sm font-semibold uppercase tracking-[0.12em] text-sky-200">
                                    Recommended Flow
                                </div>
                                <div className="mt-4 text-2xl font-semibold tracking-tight">
                                    AdminPage → React Mount → API / Service
                                </div>
                                <Separator.Root className="my-5 h-px bg-white/15" decorative />
                                <div className="space-y-3 text-sm leading-6 text-slate-200">
                                    <div>后台页类负责菜单与 enqueue。</div>
                                    <div>React 页面负责交互和局部状态。</div>
                                    <div>真实数据通过 REST API、本地化设置或后端 Service 提供。</div>
                                </div>
                            </div>
                        </div>
                    </Tabs.Content>

                    <Tabs.Content value="routing" className="mt-8 focus-visible:outline-none">
                        <div className="rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_10px_30px_rgba(15,23,42,0.04)]">
                            <div className="text-lg font-semibold text-slate-950">后台数据入口建议</div>
                            <div className="mt-4 grid gap-4 md:grid-cols-2">
                                <div className="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                                    <div className="text-sm font-semibold text-slate-900">本地化设置</div>
                                    <div className="mt-2 text-sm leading-6 text-slate-600">
                                        适合注入 `rest root`、`nonce`、当前页面上下文和少量初始化配置。
                                    </div>
                                </div>
                                <div className="rounded-2xl border border-slate-100 bg-slate-50 px-4 py-4">
                                    <div className="text-sm font-semibold text-slate-900">REST API</div>
                                    <div className="mt-2 text-sm leading-6 text-slate-600">
                                        适合列表数据、保存动作、远程筛选和需要权限校验的后台交互。
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Tabs.Content>
                </Tabs.Root>
            </div>
        </div>
    );
}
