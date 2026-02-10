import { useEffect } from "react";

export function useFavicon() {
  useEffect(() => {
    const links = document.querySelectorAll<HTMLLinkElement>("link[rel~='icon']");
    const darkQuery = window.matchMedia("(prefers-color-scheme: dark)");

    const updateFavicon = (e?: MediaQueryListEvent) => {
      const isDark = e ? e.matches : darkQuery.matches;
      links.forEach(link => {
        const size = link.getAttribute("sizes") || "32x32";
        link.href = isDark
          ? `/favicons/favicon-dark-${size}.png`
          : `/favicons/favicon-light-${size}.png`;
      });
    };

    updateFavicon();
    darkQuery.addEventListener("change", updateFavicon);
    return () => darkQuery.removeEventListener("change", updateFavicon);
  }, []);
}
