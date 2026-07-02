import * as React from 'react';
import { Slot } from '@radix-ui/react-slot';
import { cva, type VariantProps } from 'class-variance-authority';

import { cn } from '@/lib/utils';

const buttonVariants = cva(
    'wp-admin-template-button inline-flex items-center justify-center gap-2 rounded-full border text-sm font-semibold transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-60',
    {
        variants: {
            variant: {
                primary: 'border-transparent bg-slate-900 text-white hover:bg-slate-700 focus-visible:ring-slate-900',
                secondary: 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50 focus-visible:ring-slate-400',
                ghost: 'border-transparent bg-transparent text-slate-600 hover:bg-slate-100 focus-visible:ring-slate-300',
            },
            size: {
                sm: 'min-h-10 px-4',
                md: 'min-h-11 px-5',
                lg: 'min-h-12 px-6 text-base',
            },
        },
        defaultVariants: {
            variant: 'primary',
            size: 'md',
        },
    }
);

export interface ButtonProps
    extends React.ButtonHTMLAttributes<HTMLButtonElement>,
        VariantProps<typeof buttonVariants> {
    asChild?: boolean;
}

/**
 * 通用按钮组件
 *
 * 为模板后台提供可复用的 React + Radix 按钮原语。
 */
export function Button({ asChild = false, className, size, variant, ...props }: ButtonProps): React.JSX.Element {
    const Comp = asChild ? Slot : 'button';

    return <Comp className={cn(buttonVariants({ variant, size }), className)} {...props} />;
}
