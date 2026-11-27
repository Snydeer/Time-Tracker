// Loaded in <head> so dark mode is applied before the page paints.
try {
  if (localStorage.getItem("darkmode") === "true") document.documentElement.classList.add("dark");
} catch (e) {
  /* storage blocked */
}
