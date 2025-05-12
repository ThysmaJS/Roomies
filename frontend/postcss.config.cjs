// Configuration pour PostCSS avec @tailwindcss/postcss7-compat
module.exports = {
  plugins: {
    '@tailwindcss/postcss7-compat': {
      config: './tailwind.config.cjs'
    },
    autoprefixer: {},
  },
}
