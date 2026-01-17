import { createTheme } from "@mantine/core";

// theme.ts
export const theme = createTheme({
  primaryColor: 'violet',
  fontFamily: 'Inter, sans-serif',
  radius: {
    sm: '4px',
    md: '8px',
  },
  autoContrast: true,
  
  headings: {
    fontFamily: 'Roboto, sans-serif',
    sizes: {
      h1: { fontSize: '36px' },
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
