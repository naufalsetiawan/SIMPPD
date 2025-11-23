/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./**/*.{php,html,js}'],
  // ...
  theme: {
    extend: {
              fontFamily: {
        'plusjakarta': ['"Plus Jakarta Sans"', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
