const fs = require('fs');
const path = require('path');

/**
 * 修复 laravel-mix 在新版本 Node 下对 yargs 子路径的兼容问题。
 */
function main() {
    const configPath = path.resolve(__dirname, '../node_modules/laravel-mix/src/config.js');

    if (!fs.existsSync(configPath)) {
        return;
    }

    const fileContent = fs.readFileSync(configPath, 'utf8');
    const nextContent = fileContent.replace("require('yargs/yargs')", "require('yargs')");

    if (nextContent === fileContent) {
        return;
    }

    fs.writeFileSync(configPath, nextContent);
}

main();
