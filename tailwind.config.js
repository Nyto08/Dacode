/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/index.php',
    './src/view/*.{php,js}',
    './src/view/**/*.{php,js}',
  ],
  theme: {
    fontSize: {
      'sm': '0.8rem',
      'base': '1rem',
      'lg': '1.1rem',
      'xl': '1.25rem',
      '2xl': '1.563rem',
      '3xl': '1.953rem',
      '4xl': '2.441rem',
      '5xl': '3.052rem',
    },
    extend: {
      fontFamily: {
        sans: ['Libre Franklin', 'sans-serif', 'Verdana', 'Geneva', 'Tahoma']
      },
      colors: {
        // dark theme
        'primary-light-dm': '#e7d1bb',
        'primary-regular-dm': '#d6c1ab', // #c8b39e
        'primary-dark-dm': '#84725e',
        'accent-regular-dm': '#a096a5',
        'accent-dark-dm': '#463e4b',
        'subtl-regular-dm': '#a096a2',
        'subtl-dark-dm': '#847a86',
        'nightsky-light-dm': '#3d3f5b',
        'nightsky-regular-dm': '#252841',
        'nightsky-dark-dm': '#151931',
        // light theme
        'primary-light-lm': '#ff6600',
        'primary-regular-lm': '#ff983f',
        'primary-dark-lm': '#ffffa1',
        'accent-regular-lm': '#F5F5F5',
        'accent-dark-lm': '#929292',
        'subtl-regular-lm': '#FFFFFF',
        'subtl-dark-lm': '#F5F5F5',
        'nightsky-light-lm': '#CCCCCC',
        'nightsky-regular-lm': '#444648',
        'nightsky-dark-lm': '#1D1F21',
      },
    },
  },
  plugins: [],
}

