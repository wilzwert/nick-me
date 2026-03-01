import { createTheme, type MantineTheme } from "@mantine/core";

// theme.ts
export const theme = createTheme({
  primaryColor: 'cyan',
  fontFamily: "'Major Mono Display', monospace",
  radius: {
    sm: '4px',
    md: '8px',
  },
  autoContrast: true,
  
  headings: {
    // fontFamily: 'Poppins, sans-serif',
    fontFamily: "'Press Start 2P', sans-serif",
    sizes: {
      h1: { fontSize: '66px', fontWeight: '600' },
    },
  },

  components: {
      Card: {
        styles: () => ({
          root: {
            backgroundColor: 'light-dark(var(--mantine-color-white), var(--mantine-color-black))',
            border: '4px solid light-dark(black, white)',
            boxShadow: '6px 6px 0 black',
            transition: 'transform 100ms ease',

            '&:hover': {
              transform: 'translate(-2px, -2px)',
              boxShadow:
                '8px 8px 0 light-dark(black, white)',
            },
          },
        }),
        defaultProps: {
          radius: 'none',
          shadow: 'sm',
          padding: 'lg',
        },
      },
    },
});