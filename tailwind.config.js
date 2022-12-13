/** @type {import('tailwindcss').Config} */
module.exports = {
  mode: 'jit',
  content: ["./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    './resources/js/**/*.vue',
    "./node_modules/flowbite/**/*.js"
  ],
  darkMode: 'class',
  theme: {
    extend: {},
  },
  plugins: [
    require('flowbite/plugin')
  ],
  important: '.fiver-metric-class'
}