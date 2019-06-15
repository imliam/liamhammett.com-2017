module.exports = {
    theme: {
        fontFamily: {
            sans: [
                '-apple-system',
                'BlinkMacSystemFont',
                'Segoe UI',
                'Roboto',
                'Helvetica Neue',
                'Arial',
                'Noto Sans',
                'sans-serif',
                'Apple Color Emoji',
                'Segoe UI Emoji',
                'Segoe UI Symbol',
                'Noto Color Emoji',
            ],
            mono: [
                'IBM Plex Mono',
                'Monaco',
                'Consolas',
                'Liberation Mono',
                'Courier New',
                'monospace',
            ],
            serif: [
                'Merriweather',
                'Georgia',
                'Cambria',
                '"Times New Roman"',
                'Times',
                'serif',
            ],
            slab: [
                'Zilla Slab',

                // Fall back to sans-serif stack
                '-apple-system',
                'BlinkMacSystemFont',
                'Segoe UI',
                'Roboto',
                'Helvetica Neue',
                'Arial',
                'Noto Sans',
                'sans-serif',
                'Apple Color Emoji',
                'Segoe UI Emoji',
                'Segoe UI Symbol',
                'Noto Color Emoji',
            ]
        },
        extend: {
            borderWidth: {
                3: '3px',
                5: '5px',
            },
            colors: {
                'twitter': '#1da1f2',
                'stack-overflow': '#f48024',
                'dribbble': '#ea4c89',
                'github': '#4078c0',
                'medium': '#00ab6c',
            },
            fontSize: {
                xxs: '0.65rem',
            },
            lineHeight: {
                relaxed: 1.75,
            },
        },
    },
    variants: {
        borderColor: ['focus-within', 'hover', 'focus'],
    },
};
