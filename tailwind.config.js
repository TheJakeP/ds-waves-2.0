module.exports = {
  purge: [],
  important: true,
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
      fontFamily: {
        montserrat: ['Montserrat', 'serif'],
        roboto: ['Roboto', 'serif'],
        arial: ['arial', 'serif'],
      },
      colors: {
        "waves-black":          '#2B2B2B', 
        "hex-2B2B2B":           '#2B2B2B', 
        "menu-inactive":        '#B9BDC4',
        "waves-blue":           '#0D70E3',
        "waves-blue-hover":     '#86B7F1',
        "accent-gray":          '#e5e6ea',
        "input-gray":           '#bfc3c9',
        "waves-dark-gray":      '#90969a',
        "hex-707070":           '#707070',
        "waves-placeholder":    '#9ca3af',
        "waves-table-head":     '#656b6f',
        "waves-table-border":   '#eff0f4', 
        "hex-c6cad0":           '#c6cad0',
        "hex-646B6F":           '#646B6F',
        "hex-8F969A":           '#8F969A',
        "hex-E4E6EA":           '#E4E6EA',
        "hex-B9BDC4":           '#B9BDC4',
        "hex-D9D9D9":           '#D9D9D9',
        "hex-13252C":           '#13252C',
        "hex-EDEFF4":           '#EDEFF4',
        "hex-1473E2":           '#1473E2',
        "hex-289500":           '#289500',
        "hex-EF9300":           '#EF9300',
        "hex-EB0000":           '#EB0000',
        "hex-1CB809":           '#1CB809',
        "hex-A0AEC0":           '#A0AEC0',
      },
    }, 
  },
  variants: {
    extend: {
      borderColor: ['active'],
      backgroundColor: ['active'],
      textColor: ['active'],
    },
  },
  plugins: [],
}