const dotenvExpand = require('dotenv-expand');
dotenvExpand.expand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: '../../public/build-quotes',
        emptyOutDir: true,
        manifest: true,
    },
    plugins: [
        laravel({
            publicDirectory: '../../public',
            buildDirectory: 'build-quotes',
            input: [
                __dirname + '/Resources/assets/sass/app.scss',
                //__dirname + '/Resources/assets/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
