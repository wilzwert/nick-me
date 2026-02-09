import { createTheme } from "@mantine/core";

// theme.ts
export const theme = createTheme({
  primaryColor: 'cyan',
  fontFamily: 'Inter, sans-serif',
  radius: {
    sm: '4px',
    md: '8px',
  },
  autoContrast: true,
  
  headings: {
    fontFamily: 'Poppins, sans-serif',
    sizes: {
      h1: { fontSize: '66px', fontWeight: '600' },
    },
  },
  components: {
      Card: {
        defaultProps: {
          radius: 'md',
          shadow: 'sm',
          padding: 'lg',
        },
      },
    },
});