import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

/**
 * 合并组件类名
 *
 * 统一处理条件类名与 Tailwind 冲突类名。
 *
 * @param inputs 类名输入
 * @returns 合并后的类名字符串
 */
export function cn(...inputs: ClassValue[]): string {
    return twMerge(clsx(inputs));
}
