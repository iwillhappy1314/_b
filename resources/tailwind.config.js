/** @type {import('tailwindcss').Config} */

// 1. 使用 CommonJS 语法正确引入 fluid-tailwind 核心组件
const { default: fluid, extract, screens, fontSize } = require('fluid-tailwind');

module.exports = {
    content   : [
        files: [
          '../src/**/*.php',
          '../templates/**/*.php',
        ],
        extract,
    ],
    //darkMode: false, // or 'media' or 'class'
    theme   : {
        screens  : {
          'sm': '640px',
          // => @media (min-width: 640px) { ... }
    
          'md': '768px',
          // => @media (min-width: 768px) { ... }
    
          'lg': '1024px',
          // => @media (min-width: 1024px) { ... }
    
          'xl': '1280px',
          // => @media (min-width: 1280px) { ... }
    
          '2xl': '1536px',
          // => @media (min-width: 1536px) { ... }
    
          '-2xl': {'max': '1535px'},
          // => @media (max-width: 1535px) { ... }
    
          '-xl': {'max': '1279px'},
          // => @media (max-width: 1279px) { ... }
    
          '-lg': {'max': '1023px'},
          // => @media (max-width: 1023px) { ... }
    
          '-md': {'max': '767px'},
          // => @media (max-width: 767px) { ... }
    
          '-sm': {'max': '639px'},
          // => @media (max-width: 639px) { ... }
        },
        fontSize,
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins : [
        fluid,
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio')
    ],
};
