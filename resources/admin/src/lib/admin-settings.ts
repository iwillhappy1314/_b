/**
 * 后台脚本配置
 *
 * 统一读取 WordPress 注入到页面中的后台设置。
 */
export type AdminSettings = {
    root: string;
    nonce: string;
};

declare global {
    interface Window {
        wenpriseSpaceNameAdminSettings?: AdminSettings;
    }
}

/**
 * 获取后台设置
 *
 * @returns 后台设置对象
 */
export function getAdminSettings(): AdminSettings {
    return {
        root: window.wenpriseSpaceNameAdminSettings?.root ?? '',
        nonce: window.wenpriseSpaceNameAdminSettings?.nonce ?? '',
    };
}
