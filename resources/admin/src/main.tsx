import React from 'react';
import { createRoot } from 'react-dom/client';

import App from '@/App';

/**
 * 启动后台 React 应用
 *
 * 在 WordPress 后台挂载默认模板应用。
 */
function bootstrap(): void {
    const mountNode = document.getElementById('_b-admin');

    if (!mountNode) {
        return;
    }

    const root = createRoot(mountNode);
    root.render(
        <React.StrictMode>
            <App />
        </React.StrictMode>
    );
}

bootstrap();
