import { renderHook, act } from '../../test-utils/index'
import { describe, it, expect, vi, beforeEach } from "vitest";
import { useFavicon } from "./useFavicon";

describe("useFavicon", () => {
  let links: HTMLLinkElement[];

  beforeEach(() => {
    // DOM reset
    document.head.innerHTML = `
      <link rel="icon" sizes="16x16" href="initial.png">
      <link rel="icon" sizes="32x32" href="initial.png">
      <link rel="icon" sizes="64x64" href="initial.png">
    `;
    links = Array.from(document.querySelectorAll("link[rel~='icon']"));
  });

  it("sets favicon hrefs to dark mode if matchMedia matches", () => {
    // Simulate dark mode
    (window.matchMedia as any).mockImplementation((query: string) => ({
      matches: true,
      media: query,
      onchange: null,
      addListener: vi.fn(),
      removeListener: vi.fn(),
      addEventListener: vi.fn(),
      removeEventListener: vi.fn(),
      dispatchEvent: vi.fn(),
    }));

    renderHook(() => useFavicon());

    links.forEach((link) => {
      const size = link.getAttribute("sizes");
      expect(link.href).toContain(`favicon-dark-${size}.png`);
    });
  });

  it("sets favicon hrefs to light mode if matchMedia does not match", () => {
    // Simulate light mode
    (window.matchMedia as any).mockImplementation((query: string) => ({
      matches: false,
      media: query,
      onchange: null,
      addListener: vi.fn(),
      removeListener: vi.fn(),
      addEventListener: vi.fn(),
      removeEventListener: vi.fn(),
      dispatchEvent: vi.fn(),
    }));

    renderHook(() => useFavicon());

    links.forEach((link) => {
      const size = link.getAttribute("sizes");
      expect(link.href).toContain(`favicon-light-${size}.png`);
    });
  });

  it("updates favicon on theme change", () => {
    let listener: ((e: any) => void) | null = null;
    (window.matchMedia as any).mockImplementation(() => ({
      matches: false,
      media: "(prefers-color-scheme: dark)",
      addEventListener: (event: string, cb: Function) => {
        listener = cb as any;
      },
      removeEventListener: vi.fn(),
      addListener: vi.fn(),
      removeListener: vi.fn(),
      onchange: null,
      dispatchEvent: vi.fn(),
    }));

    renderHook(() => useFavicon());

    // simulate theme switch
    act(() => {
      listener?.({ matches: true });
    });

    links.forEach((link) => {
      const size = link.getAttribute("sizes");
      expect(link.href).toContain(`favicon-dark-${size}.png`);
    });
  });
});
