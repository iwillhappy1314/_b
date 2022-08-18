module.exports = {
    content   : [
        './index.html',
        './templates/**/*.php',
        './source/**/*.{vue,js,ts,jsx,tsx}',
    ],
    //darkMode: false, // or 'media' or 'class'
    theme   : {
        extend: {},
    },
    variants: {
        extend: {},
    },
    plugins : [
        require('@tailwindcss/typography'),
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/aspect-ratio')
    ],
};