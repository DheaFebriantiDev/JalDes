/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],

  theme: {
    extend: {
      colors: {
        forest: {
          50:  '#f3f6f4',
          100: '#e1e9e3',
          200: '#c3d3c7',
          300: '#9db4a3',
          400: '#739080',
          500: '#557163',
          600: '#3f5c44', // warna utama logo
          700: '#344b38',
          800: '#2b3d2f',
          900: '#243228'
        }
      }
    }
  },
  plugins: []
}