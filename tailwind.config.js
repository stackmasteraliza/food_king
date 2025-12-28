/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
      extend: {
          backgroundImage: {
              'footer': "url('/themes/default/images/bg/footer.png')",
              'installer': "url('/themes/default/images/bg/installer.jpg')",
          },
          screens: {
              'xst': {'min': '0px', 'max': '640px'},
              'xh': {'min': '0px', 'max': '767px'},
              'xsd': {'min': '0px', 'max': '450px'},
          },
          zIndex: {
              "60": "60",
              "70": "70",
              "80": "80",
          },
          fontFamily: {
              "rubik": ["'Rubik', sans-serif"],
              "public": ["'Public Sans', sans-serif"],
              "awesome": ["'Font Awesome 6 Free'"],
              "lab": ["'Lab'"]
          },
          colors: {
              "heading": "#1F1F39",
              "paragraph": "#6E7191",
              "placeholder": "#A0A3BD",
              "primary": "rgb(255 0 0 / <alpha-value>)",
              "primary-light": "rgb(220 234 255 / <alpha-value>)",
          },
          boxShadow: {
              "sidebar": "15px 0px 25px 0px rgba(0, 0, 0, 0.08)",
              "sidebar-right": "15px 0px 25px 0px rgb(0 0 0 / 12%)",
              "db-sidebar-left": "0 0.125rem -0.375rem 0 rgb(161 172 184 / 12%)",
              "db-sidebar-right": "0 0.125rem 0.375rem 0 rgb(161 172 184 / 12%)",
              "sidebar-left": "-15px 0px 25px 0px rgb(0 0 0 / 12%)",
              "db-card": "0 2px 6px 0 rgb(67 89 113 / 12%)",
              "xl-top": "0 -20px 25px -5px rgb(0 0 0 / 0.1), 0 -8px 10px -6px rgb(0 0 0 / 0.1)",
              "xs": "0px 6px 32px 0px rgba(0, 0, 0, 0.04)",
              "more": "0 0.125rem 0.25rem 0 rgb(23 114 255/ 40%)",
              "coupon": "0px 4px 8px rgba(0, 0, 0, 0.04), 0px 0px 2px rgba(0, 0, 0, 0.06), 0px 0px 1px rgba(0, 0, 0, 0.04)",
              "checkRound": "0 2px 4px 0 rgb(105 108 255 / 40%)",
              "filter": "0px 8px 16px rgba(23, 31, 70, 0.08)",
              "cardCart": "0px 8px 16px rgba(23, 31, 70, 0.08)",
              "paper": "0px 4px 40px rgba(23, 31, 70, 0.16)",
              "avatar": "0px 6px 10px rgba(23 114 255, 0.15)",
              "menu": "0px 4px 16px rgba(126, 133, 142, 0.16)",
              "logo": "0px 0px 8px rgba(51, 48, 48, 0.12)",
              "button": "0px 6px 32px rgba(23 114 255, 0.32)",
              "drawer-right": "0px -15px 25px 0px rgb(0 0 0 / 15%)",
              "drawer-left": "0px 15px 25px 0px rgb(0 0 0 / 15%)",
              "pink": "0px 6px 32px rgba(23 114 255, 0.32)",
              "blue": "0px 6px 32px rgba(0, 116, 155, 0.32)",
          },
          dropShadow: {
              "category": "2px 4px 8px rgba(0, 0, 0, 0.25)",
          }
      },
  },
plugins: [],
}
