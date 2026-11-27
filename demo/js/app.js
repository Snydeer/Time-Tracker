// Page logic for the demo. Each handler below corresponds to one of the
// original PHP pages (named in the comment), reusing its messages and rules.

import * as store from "./store.js";
import { h, go, logout, applyDarkMode, toggleDarkMode, renderAppChrome, renderDemoBar } from "./layout.js";

const $ = (sel) => document.querySelector(sel);
const body = document.body;

function message(target, text, isError = true) {
  const el = typeof target === "string" ? $(target) : target;
  el.replaceChildren(h("p", { class: isError ? "error-message" : "success-message", text }));
}

function fillEmployeeSelect(select, company) {
  select.replaceChildren(
    ...store.employeesOf(company).map((e) => h("option", { value: e.employees_uid, text: e.employees_name }))
  );
}

// ---------- index.php ----------
function homePage() {
  const s = store.session();
  const btn = $("#auth-button");
  if (s) {
    btn.textContent = "log-out";
    btn.addEventListener("click", logout);
  } else {
    btn.addEventListener("click", () => go("loginpicker.html"));
  }
}

// ---------- login.php / loginemployee.php ----------
function loginPage(type) {
  const form = $("#login-form");
  const demo = type === "manager" ? store.DEMO_MANAGER : store.DEMO_EMPLOYEE;

  const params = new URLSearchParams(location.search);
  if (params.get("signedup")) message("#login-error", "You have signed up! Log in with your new account.", false);

  $("#use-demo").addEventListener("click", () => {
    form.uid.value = demo.uid;
    form.pwd.value = demo.pwd;
    form.requestSubmit();
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const uid = form.uid.value.trim();
    const pwd = form.pwd.value;
    if (!uid || !pwd) return message("#login-error", "Fill in all fields!");

    const row = type === "manager" ? store.findUser(uid) : store.findEmployee(uid);
    if (!row) return message("#login-error", "Cannot find user!");
    const ok = await store.verifyPassword(pwd, type === "manager" ? row.users_pwd : row.employees_pwd);
    if (!ok) return message("#login-error", "Incorrect Login Information!");

    store.startSession(type, type === "manager" ? row.users_uid : row.employees_uid);
    go("dashboard.html");
  });
}

// ---------- signup.php (managers / business owners) ----------
function signupPage() {
  const form = $("#signup-form");
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const v = Object.fromEntries(new FormData(form));
    for (const k of Object.keys(v)) if (k !== "pwd" && k !== "pwdrepeat") v[k] = v[k].trim();

    if (!v.name || !v.email || !v.uid || !v.pwd || !v.pwdrepeat || !v.companyname) return message("#signup-error", "Fill in all fields!");
    if (!/^[a-zA-Z0-9]+$/.test(v.uid)) return message("#signup-error", "Choose a proper username!");
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.email)) return message("#signup-error", "Choose a proper email!");
    if (v.pwd !== v.pwdrepeat) return message("#signup-error", "Passwords don't match!");
    if (store.findUser(v.uid) || store.findUser(v.email) || store.findEmployee(v.uid)) return message("#signup-error", "Username already taken!");
    if (store.companyTaken(v.companyname)) return message("#signup-error", "Your company already has an account!");

    store.addUser({
      users_name: v.name,
      users_email: v.email,
      users_uid: v.uid,
      users_pwd: await store.hashPassword(v.pwd),
      users_company: v.companyname,
    });
    go("login.html?signedup=1");
  });
}

// ---------- dashboard.php ----------
function dashboardPage(user) {
  const latest = store.announcementsFor(user.company).at(-1);
  const box = $("#dashboard");
  const recent = [h("h2", { text: "Recent Announcement" }), h("p", { text: latest ? latest.announcement_text : "No announcements yet." })];

  if (user.type === "manager") {
    $("#dashboard-title").textContent = "Manager Dashboard";
    const team = store.employeesOf(user.company);
    box.replaceChildren(
      h("h2", { text: `Welcome, ${user.name}!` }),
      h("p", { text: `Company: ${user.company} · ${team.length} employee${team.length === 1 ? "" : "s"}` }),
      ...recent
    );
  } else {
    $("#dashboard-title").textContent = "Employee Dashboard";
    box.replaceChildren(
      h("h2", { text: `Welcome, ${user.name}!` }),
      h("h2", { text: "Current Wage" }),
      h("p", { text: `Current Wage: $${store.fmt.money(store.payRate(user.uid))} per hour` }),
      h("h2", { text: "Total Hours in Past 7 days" }),
      h("p", { text: `Total Hours in the Past 7 Days: ${store.hoursPast7Days(user.uid)} hours` }),
      ...recent
    );
  }
}

// ---------- profile.php ----------
function profilePage(user) {
  $("#profile-welcome").textContent = `Welcome, ${user.name}!`;
  const b = (label, href) => h("button", { class: "main", type: "button", onclick: () => go(href) }, h("span", { text: label }));
  const buttons =
    user.type === "manager"
      ? [b("Change Password", "changepassword.html"), b("Update Employee Pay", "changepayroll.html"), b("View Employees", "viewallemployees.html"), b("Settings", "settings.html")]
      : [b("Change Password", "changepassword.html"), b("Payroll", "employeepay.html"), b("Settings", "settings.html")];
  $("#profile-buttons").replaceChildren(...buttons);
}

// ---------- settings.php ----------
function settingsPage(user) {
  $("#settings-name").textContent = `Name: ${user.name}`;
  $("#settings-uid").textContent = `Username: ${user.uid}`;
  $("#settings-company").textContent = `Company: ${user.company}`;
  $("#settings-role").textContent = `Account type: ${user.type === "manager" ? "Company Manager" : "Employee"}`;
  $("#settings-time").addEventListener("click", () => go(user.type === "manager" ? "timemanager.html" : "timeview.html"));
}

// ---------- create.php (manager creates an employee account) ----------
function generateUsername(name) {
  // generateUsername() from includes/create.inc.php: lowercase name, no
  // spaces, plus a random 4-digit number.
  const base = name.toLowerCase().replace(/[^a-z0-9]/g, "") || "employee";
  let uid;
  do uid = base + (1000 + Math.floor(Math.random() * 9000));
  while (store.findEmployee(uid) || store.findUser(uid));
  return uid;
}

function createPage(user) {
  const form = $("#create-form");
  form.company.value = user.company;
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    if (!name || !email) return message("#create-result", "Fill in all fields!");
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return message("#create-result", "Choose a proper email!");

    const uid = generateUsername(name);
    store.addEmployee({
      employees_name: name,
      employees_email: email,
      employees_uid: uid,
      employees_pwd: "plain:" + uid, // temporary password = username, as in the original
      employees_company: user.company,
    });
    form.reset();
    form.company.value = user.company;
    $("#create-result").replaceChildren(
      h("p", { class: "success-message", text: "You have created the employee's account!" }),
      h("p", { text: `Username: ${uid}` }),
      h("p", { text: `Temporary password: ${uid}` }),
      h("p", { class: "hint", text: "Set their pay under Profile → Update Employee Pay. Log out and use the Employee login to try this account." })
    );
  });
}

// ---------- viewallemployees.php ----------
function employeesPage(user) {
  const rows = store.employeesOf(user.company).map((e) =>
    h("tr", {}, h("td", { text: e.employees_name }), h("td", { text: e.employees_email }), h("td", { text: e.employees_uid }))
  );
  $("#employee-rows").replaceChildren(...(rows.length ? rows : [h("tr", {}, h("td", { colspan: 3, text: "No employees yet." }))]));
}

// ---------- timemanager.php ----------
function trackRows(tracks, cols) {
  return tracks.map((t) => {
    const done = t.elapsed_time !== null;
    return h("tr", {}, cols.map((c) => h("td", { text: c(t, done) })));
  });
}

function timeManagerPage(user) {
  $("#tm-welcome").textContent = `Welcome, Manager of ${user.company}!`;
  const form = $("#tm-form");
  fillEmployeeSelect(form.employee_id, user.company);
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const uid = form.employee_id.value;
    if (!uid) return;
    const emp = store.findEmployee(uid);
    const tracks = store.tracksFor(uid);
    $("#tm-title").textContent = `Time Tracking Records for ${emp.employees_name} (${uid})`;
    $("#tm-rows").replaceChildren(
      ...(tracks.length
        ? trackRows(tracks, [
            (t) => store.fmt.longDate24(t.start_time),
            (t, done) => (done ? store.fmt.longDate24(t.end_time) : "Currently clocked in"),
            (t, done) => (done ? store.fmt.hms(t.elapsed_time) : "-"),
          ])
        : [h("tr", {}, h("td", { colspan: 3, text: "No time tracked yet." }))])
    );
    $("#tm-results").hidden = false;
  });
}

// ---------- createannouncement.php -> confirm_announcement.php -> processannouncement.php ----------
function createAnnouncementPage(user) {
  const form = $("#announcement-form");
  const confirmBox = $("#announcement-confirm");
  form.expiration_date.min = store.toDateInput(new Date());

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const text = form.announcement_text.value.trim();
    if (!text) return message("#announcement-error", "Please write an announcement.");
    $("#announcement-error").replaceChildren();
    $("#confirm-text").textContent = `Announcement Text: ${text}`;
    $("#confirm-date").textContent = `Expiration Date: ${form.expiration_date.value || "none"}`;
    form.hidden = true;
    confirmBox.hidden = false;
  });
  $("#confirm-yes").addEventListener("click", () => {
    store.addAnnouncement(user.company, form.announcement_text.value.trim(), form.expiration_date.value);
    go("dashboard.html");
  });
  $("#confirm-cancel").addEventListener("click", () => {
    confirmBox.hidden = true;
    form.hidden = false;
  });
}

// ---------- viewannouncements.php ----------
function viewAnnouncementsPage(user) {
  const list = store.announcementsFor(user.company);
  const box = $("#announcements");
  if (!list.length) return box.replaceChildren(h("p", { text: "No Announcements" }));
  box.classList.add("scroll");
  box.replaceChildren(
    ...list.flatMap((a) => [
      h(
        "div",
        { class: "announcement" },
        h("p", { text: a.announcement_text }),
        h("p", {}, h("strong", { text: "Date Posted:" }), " " + store.fmt.longDate(a.date_posted))
      ),
      h("hr"),
    ])
  );
}

// ---------- viewpayroll.php / employeepay.php ----------
function defaultPeriod(form) {
  const end = new Date();
  const start = new Date();
  start.setDate(start.getDate() - 13);
  form.start_date.value = store.toDateInput(start);
  form.end_date.value = store.toDateInput(end);
}

function payReport(target, name, uid, start, end, showUid) {
  const r = store.payReport(uid, start, end);
  const d = (s) => store.fmt.longDate(new Date(s + "T00:00:00").getTime());
  $(target).replaceChildren(
    h("h2", { text: "Employee Pay Report" }),
    h("p", { text: `Employee: ${name}` }),
    showUid ? h("p", { text: `Employee Username: ${uid}` }) : null,
    h("p", { text: `Start Date: ${d(start)}` }),
    h("p", { text: `End Date: ${d(end)}` }),
    h("p", { text: `Pay Rate: $${store.fmt.money(r.rate)} per hour` }),
    h("p", { text: `Total Hours: ${r.hours} hours tracked` }),
    h("p", { text: `Total Pay: $${store.fmt.money(r.total)}` })
  );
}

function checkDates(form, target) {
  if (!form.start_date.value || !form.end_date.value) return message(target, "Pick a start and end date."), false;
  if (form.start_date.value > form.end_date.value) return message(target, "The start date must be before the end date."), false;
  return true;
}

function managerPayrollPage(user) {
  const form = $("#payroll-form");
  fillEmployeeSelect(form.employees_uid, user.company);
  defaultPeriod(form);
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!form.employees_uid.value || !checkDates(form, "#pay-report")) return;
    const emp = store.findEmployee(form.employees_uid.value);
    payReport("#pay-report", emp.employees_name, emp.employees_uid, form.start_date.value, form.end_date.value, true);
  });
}

function employeePayPage(user) {
  const form = $("#payroll-form");
  defaultPeriod(form);
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!checkDates(form, "#pay-report")) return;
    payReport("#pay-report", user.name, user.uid, form.start_date.value, form.end_date.value, false);
  });
}

// ---------- changepayroll.php + update_pay.php ----------
function changePayrollPage(user) {
  const rows = store.employeesOf(user.company).map((e) =>
    h(
      "tr",
      {},
      h("td", { text: e.employees_name }),
      h(
        "td",
        {},
        h("input", {
          type: "number",
          min: "0",
          max: "1000",
          step: "0.01",
          name: e.employees_uid,
          value: store.fmt.money(store.payRate(e.employees_uid)),
          "aria-label": `Pay rate for ${e.employees_name}`,
        })
      )
    )
  );
  $("#pay-rows").replaceChildren(...rows);
  $("#pay-form").addEventListener("submit", (e) => {
    e.preventDefault();
    const inputs = [...e.target.querySelectorAll("input[type=number]")];
    if (inputs.some((i) => i.value === "" || !(Number(i.value) >= 0))) return message("#pay-result", "Pay amounts must be numbers of 0 or more.");
    for (const i of inputs) store.setPayRate(i.name, Math.round(Number(i.value) * 100) / 100);
    message("#pay-result", "Pay rates updated!", false);
  });
}

// ---------- timetracker.php ----------
let ticker = null;

function timeTrackerPage(user) {
  const render = () => {
    clearInterval(ticker);
    const last = store.mostRecentTrack(user.uid);
    const tracking = last && last.elapsed_time === null;
    const box = $("#tracker-box");

    if (tracking) {
      const live = h("p", { class: "live-timer" });
      const tick = () => (live.textContent = `Elapsed: ${store.fmt.hms(Math.floor((Date.now() - last.start_time) / 1000))}`);
      tick();
      ticker = setInterval(tick, 1000);
      box.replaceChildren(
        h("div", { class: "title", text: "Currently Tracking" }),
        h("p", { text: `Start Time: ${store.fmt.sql(last.start_time)}` }),
        live,
        h("button", { class: "timeSheetBut", type: "button", onclick: () => (store.clockOut(user.uid), render()) }, h("span", { text: "Clock out" }))
      );
    } else {
      box.replaceChildren(
        h("div", { class: "title", text: "Time Not Tracked" }),
        h("button", { class: "timeSheetBut", type: "button", onclick: () => (store.clockIn(user.uid), render()) }, h("span", { text: "Clock in" }))
      );
    }

    const details = $("#tracker-details");
    if (!last) return details.replaceChildren(h("p", { text: "No time tracking details available." }));
    const parts = [h("h2", { text: "Most Recent Time Tracking Details" }), h("p", { text: `Start Time: ${store.fmt.longDateTime(last.start_time)}` })];
    if (tracking) parts.push(h("p", { text: "Currently tracking" }));
    else {
      const s = last.elapsed_time;
      parts.push(
        h("p", { text: `End Time: ${store.fmt.longDateTime(last.end_time)}` }),
        h("p", { text: `Elapsed Time: ${Math.floor(s / 3600)} hours, ${Math.floor(s / 60) % 60} minutes, ${s % 60} seconds` })
      );
    }
    details.replaceChildren(...parts);
  };
  render();
}

// ---------- timeview.php ----------
function timeViewPage(user) {
  const tracks = store.tracksFor(user.uid);
  $("#timesheet-rows").replaceChildren(
    ...(tracks.length
      ? trackRows(tracks, [
          (t) => store.fmt.ymd(t.start_time),
          (t) => store.fmt.clock(t.start_time),
          (t, done) => (done ? store.fmt.clock(t.end_time) : "Clocked in"),
          (t, done) => (done ? store.fmt.hms(t.elapsed_time) : "-"),
          (t, done) => (done ? String(Math.floor(t.elapsed_time / 3600)) : "-"),
        ])
      : [h("tr", {}, h("td", { colspan: 5, text: "No time tracked yet. Clock in from Time Punch." }))])
  );
}

// ---------- changepassword.php ----------
function changePasswordPage(user) {
  const form = $("#password-form");
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const stored = user.type === "manager" ? user.row.users_pwd : user.row.employees_pwd;
    if (!(await store.verifyPassword(form.old_password.value, stored))) return message("#password-result", "Incorrect old password.");
    if (form.new_password.value.length < 4) return message("#password-result", "Your new password needs at least 4 characters.");
    store.setUserPassword(user.type, user.uid, await store.hashPassword(form.new_password.value));
    user.row = (user.type === "manager" ? store.findUser : store.findEmployee)(user.uid);
    form.reset();
    message("#password-result", "You have changed your password!", false);
  });
}

// ---------- router ----------

const pages = {
  home: homePage,
  loginpicker: () => {},
  login: () => loginPage("manager"),
  loginemployee: () => loginPage("employee"),
  signup: signupPage,
  dashboard: dashboardPage,
  profile: profilePage,
  settings: settingsPage,
  create: createPage,
  viewallemployees: employeesPage,
  timemanager: timeManagerPage,
  createannouncement: createAnnouncementPage,
  viewannouncements: viewAnnouncementsPage,
  viewpayroll: managerPayrollPage,
  employeepay: employeePayPage,
  changepayroll: changePayrollPage,
  timetracker: timeTrackerPage,
  timeview: timeViewPage,
  changepassword: changePasswordPage,
  about: () => {},
};

function init() {
  applyDarkMode();
  const { page, layout, auth } = body.dataset;

  // Auth checks, like the redirects at the top of the PHP pages.
  const user = store.currentUser();
  if (auth) {
    if (!user) return go("loginpicker.html");
    if (auth !== "any" && auth !== user.type) return go("dashboard.html");
  }
  if (user && ["login", "loginemployee", "loginpicker", "signup"].includes(page)) return go("dashboard.html");

  if (layout === "app") renderAppChrome();
  renderDemoBar();

  for (const el of document.querySelectorAll("[data-href]")) el.addEventListener("click", () => go(el.dataset.href));
  for (const el of document.querySelectorAll("[data-darkmode]")) el.addEventListener("click", toggleDarkMode);

  pages[page]?.(user);
  body.classList.add("ready");
}

init();
