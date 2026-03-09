
import path from 'path'; import { defineConfig, loadEnv } from 'vite'; export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, '.', ''); return {
    root: '.', plugins: [], define: { 'process.env.GEMINI_API_KEY': JSON.stringify(env.GEMINI_API_KEY || ''), }, resolve: { alias: { '@': path.resolve(__dirname, '.') }, },

    server: {
      port: 3000,
      host: '0.0.0.0'
    },

    build: {
      outDir: 'dist',
      emptyOutDir: true,

      rollupOptions: {
        input: {
          main: path.resolve(__dirname, 'index.html'),
          login: path.resolve(__dirname, 'Login.html'),
          blogs: path.resolve(__dirname, 'Blogs.html'),
          aboutus: path.resolve(__dirname, 'aboutus.html'),
          admin: path.resolve(__dirname, 'Admin.html'),
          tracnghiem: path.resolve(__dirname, 'TracNghiem.html'),
          nganhhoc: path.resolve(__dirname, 'Nganhhoc.html')
        }
      }
    }
  };
});
