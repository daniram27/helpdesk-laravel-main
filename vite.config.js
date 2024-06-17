import { defineConfig } from 'vite';
import { VitePWA } from 'vite-plugin-pwa'
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        
        VitePWA({
            registerType: 'autoUpdate',
            devOptions: {
              enabled: true
            },
            includeAssets: ['favicon.svg', 'favicon.ico', 'robots.txt', 'apple-touch-icon.png'],
            manifest: {
              name: 'ETSA',
              short_name: 'ETSA',
              description: 'E Ticketing Sampoerna Academy',
              theme_color: '#ffffff',
              icons: [
                {
                  src: 'pwa-192x192.png',
                  sizes: '192x192',
                  type: 'image/png',
                },
                {
                  src: 'pwa-512x512.png',
                  sizes: '512x512',
                  type: 'image/png',
                },
                {
                  src: 'pwa-512x512.png',
                  sizes: '512x512',
                  type: 'image/png',
                  purpose: 'any maskable',
                }
              ]
            }
          })
        ],
        build: {
          outDir: 'public/build',
        },
      });
