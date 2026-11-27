// Replaces header.php: builds the side nav and the top button bar based on
// who is logged in, plus the demo bar and dark mode.

import { session, endSession, resetDemo } from "./store.js";

// Tiny element helper. Text is always set with textContent, never innerHTML,
// so anything a visitor types is displayed as plain text.
export function h(tag, attrs = {}, ...children) {
  const el = document.createElement(tag);
  for (const [k, v] of Object.entries(attrs)) {
    if (v === null || v === undefined || v === false) continue;
    if (k === "class") el.className = v;
    else if (k === "text") el.textContent = v;
    else if (k.startsWith("on")) el.addEventListener(k.slice(2), v);
    else el.setAttribute(k, v === true ? "" : v);
  }
  for (const c of children.flat()) {
    if (c === null || c === undefined || c === false) continue;
    el.append(c instanceof Node ? c : document.createTextNode(String(c)));
  }
  return el;
}

export function go(href) {
  window.location.href = href;
}

export function logout() {
  endSession();
  go("index.html");
}

// ---------- dark mode ----------
// The original script toggled dark mode on every page load, so it flipped each
// time you navigated. The demo just applies the saved preference.

export function applyDarkMode() {
  // Set on <html> so js/theme.js can apply it before the page paints.
  document.documentElement.classList.toggle("dark", localStorage.getItem("darkmode") === "true");
}

export function toggleDarkMode() {
  const next = localStorage.getItem("darkmode") !== "true";
  localStorage.setItem("darkmode", String(next));
  applyDarkMode();
}

// ---------- side nav + header (header.php) ----------

function navButton(cls, label, onclick) {
  return h("button", { class: cls, type: "button", onclick }, h("span", { text: label }));
}

export function renderAppChrome() {
  const s = session();

  const side = h(
    "div",
    { class: "sidenav" },
    h("div", { class: "image-container" }, h("img", { src: "img/BigLogoV1.png", alt: "Agile Company logo" })),
    navButton("sideBut", "Home", () => go("index.html")),
    s
      ? [
          navButton("sideBut", "Dashboard", () => go("dashboard.html")),
          navButton("sideBut", "Profile", () => go("profile.html")),
          navButton("sideBut", "Logout", logout),
        ]
      : [navButton("sideBut", "Sign Up", () => go("signup.html")), navButton("sideBut", "Login", () => go("loginpicker.html"))],
    h("button", { id: "darkmode", class: "sideBut", type: "button", onclick: toggleDarkMode, text: "dark mode" })
  );

  let top = [];
  if (s?.type === "manager") {
    top = [
      navButton("main", "Create Employee Account", () => go("create.html")),
      navButton("main", "Employee Timesheet", () => go("timemanager.html")),
      navButton("main", "Create Announcements", () => go("createannouncement.html")),
      navButton("main", "View Announcements", () => go("viewannouncements.html")),
      navButton("main", "Pay Period", () => go("viewpayroll.html")),
    ];
  } else if (s?.type === "employee") {
    top = [
      navButton("main", "Time Punch", () => go("timetracker.html")),
      navButton("main", "Time Sheet", () => go("timeview.html")),
      navButton("main", "View Announcements", () => go("viewannouncements.html")),
    ];
  }
  const header = h("header", {}, h("div", { class: "buttons" }, top));

  document.body.prepend(side, header);
}

// ---------- demo bar (on every page) ----------

export function renderDemoBar() {
  const bar = h(
    "aside",
    { class: "demo-bar", "aria-label": "Demo mode" },
    h("strong", { text: "Demo mode" }),
    h("span", { class: "demo-bar-note", text: " · This is a static demo of a PHP + MySQL class project. Data is saved only in your browser." }),
    h(
      "span",
      { class: "demo-bar-actions" },
      h("a", { href: "about.html", text: "About this project" }),
      h("button", {
        type: "button",
        text: "Reset demo",
        onclick: () => {
          if (confirm("Reset all demo data and log out?")) {
            resetDemo();
            go("index.html");
          }
        },
      })
    )
  );
  document.body.append(bar);
}
