// Demo "database" for the static version of Time Tracker.
//
// The original app stored everything in MySQL (see ../sqlswe/oopswe.sql).
// This file recreates the same five tables in the browser's localStorage so the
// demo can run on GitHub Pages / Vercel with no server. Nothing leaves the
// visitor's browser, and "Reset demo" puts the sample data back.

const DB_KEY = "tt-demo-db-v1";
const SESSION_KEY = "tt-demo-session";
const DEMO_COMPANY = "Agile Company";

export const DEMO_MANAGER = { uid: "demomanager", pwd: "demo1234" };
export const DEMO_EMPLOYEE = { uid: "jordanlee4821", pwd: "jordanlee4821" };

// ---------- seed data ----------

// Small deterministic PRNG so the sample timesheets look the same on every reset.
function rng(seed) {
  let s = seed >>> 0;
  return () => {
    s = (s * 1664525 + 1013904223) >>> 0;
    return s / 2 ** 32;
  };
}

function seed() {
  const now = Date.now();
  const day = 86400000;
  const db = {
    users: [
      {
        users_id: 1,
        users_name: "Morgan Reyes",
        users_email: "morgan@agileco.example",
        users_uid: DEMO_MANAGER.uid,
        users_pwd: "plain:" + DEMO_MANAGER.pwd,
        users_company: DEMO_COMPANY,
      },
    ],
    employee: [],
    payroll: [],
    time_tracking: [],
    announcements: [],
    nextId: { users: 2, employee: 1, time_tracking: 1, announcements: 1 },
  };

  const staff = [
    ["Jordan Lee", "jordanlee4821", 18.5],
    ["Priya Patel", "priyapatel1207", 21.0],
    ["Sam Carter", "samcarter3390", 16.75],
    ["Alex Kim", "alexkim5512", 19.25],
  ];
  for (const [name, uid, rate] of staff) {
    insertEmployee(db, {
      employees_name: name,
      employees_email: uid.replace(/\d+$/, "") + "@agileco.example",
      employees_uid: uid,
      // Like the original: an employee's first password is their username.
      employees_pwd: "plain:" + uid,
      employees_company: DEMO_COMPANY,
    });
    db.payroll.find((p) => p.employees_uid === uid).pay_amount = rate;
  }

  // Three weeks of weekday shifts for everyone.
  const rand = rng(20240228);
  const today = new Date(now);
  today.setHours(0, 0, 0, 0);
  for (let d = 21; d >= 1; d--) {
    const date = new Date(today.getTime() - d * day);
    const dow = date.getDay();
    if (dow === 0 || dow === 6) continue;
    for (const [, uid] of staff) {
      if (rand() < 0.12) continue; // the odd day off
      const startHour = 8 + Math.floor(rand() * 3);
      const startMin = Math.floor(rand() * 4) * 15;
      const start = new Date(date);
      start.setHours(startHour, startMin, Math.floor(rand() * 60), 0);
      const elapsed = Math.round((6 + rand() * 2.5) * 3600);
      db.time_tracking.push({
        id: db.nextId.time_tracking++,
        username: uid,
        start_time: start.getTime(),
        end_time: start.getTime() + elapsed * 1000,
        elapsed_time: elapsed,
      });
    }
  }

  const notes = [
    [14, 30, "Welcome to the new time tracker! Remember to clock in when your shift starts and clock out before you leave."],
    [6, 20, "Payroll for this period closes Friday. Make sure all of your hours are in."],
    [2, 9, "Team lunch next Wednesday at noon, on the company. Let Morgan know about any dietary needs."],
  ];
  for (const [ago, expiresIn, text] of notes) {
    db.announcements.push({
      id: db.nextId.announcements++,
      announcement_text: text,
      date_posted: now - ago * day,
      expiration_date: toDateInput(new Date(now + expiresIn * day)),
      company: DEMO_COMPANY,
    });
  }
  return db;
}

// ---------- persistence ----------

let cache = null;

export function db() {
  if (cache) return cache;
  try {
    cache = JSON.parse(localStorage.getItem(DB_KEY));
  } catch {
    cache = null;
  }
  if (!cache || !Array.isArray(cache.users)) {
    cache = seed();
    save();
  }
  return cache;
}

export function save() {
  try {
    localStorage.setItem(DB_KEY, JSON.stringify(cache));
  } catch {
    /* storage full or blocked: the demo still works for this page view */
  }
}

export function resetDemo() {
  cache = seed();
  save();
  sessionStorage.removeItem(SESSION_KEY);
}

// ---------- session (stands in for PHP's $_SESSION) ----------

export function session() {
  try {
    const s = JSON.parse(sessionStorage.getItem(SESSION_KEY));
    if (!s) return null;
    if (s.type === "manager" && findUser(s.uid)) return s;
    if (s.type === "employee" && findEmployee(s.uid)) return s;
  } catch {
    /* fall through */
  }
  return null;
}

export function startSession(type, uid) {
  sessionStorage.setItem(SESSION_KEY, JSON.stringify({ type, uid }));
}

export function endSession() {
  sessionStorage.removeItem(SESSION_KEY);
}

export function currentUser() {
  const s = session();
  if (!s) return null;
  if (s.type === "manager") {
    const u = findUser(s.uid);
    return { type: "manager", uid: u.users_uid, name: u.users_name, company: u.users_company, row: u };
  }
  const e = findEmployee(s.uid);
  return { type: "employee", uid: e.employees_uid, name: e.employees_name, company: e.employees_company, row: e };
}

// ---------- passwords ----------
// The PHP version used password_hash()/password_verify(). Here new passwords
// are stored as a salted SHA-256 hash when the browser supports Web Crypto.
// (The built-in demo accounts use their published demo passwords.)

async function sha256(text) {
  const buf = await crypto.subtle.digest("SHA-256", new TextEncoder().encode(text));
  return [...new Uint8Array(buf)].map((b) => b.toString(16).padStart(2, "0")).join("");
}

export async function hashPassword(pwd) {
  if (!globalThis.crypto?.subtle) return "plain:" + pwd;
  const salt = [...crypto.getRandomValues(new Uint8Array(8))].map((b) => b.toString(16).padStart(2, "0")).join("");
  return `sha256:${salt}:${await sha256(salt + pwd)}`;
}

export async function verifyPassword(pwd, stored) {
  if (typeof stored !== "string") return false;
  if (stored.startsWith("plain:")) return stored.slice(6) === pwd;
  const [kind, salt, hash] = stored.split(":");
  if (kind !== "sha256" || !globalThis.crypto?.subtle) return false;
  return (await sha256(salt + pwd)) === hash;
}

// ---------- queries (each mirrors a query from the PHP code) ----------

export function findUser(uidOrEmail) {
  return db().users.find((u) => u.users_uid === uidOrEmail || u.users_email === uidOrEmail);
}

export function findEmployee(uidOrEmail) {
  return db().employee.find((e) => e.employees_uid === uidOrEmail || e.employees_email === uidOrEmail);
}

export function employeesOf(company) {
  return db().employee.filter((e) => e.employees_company === company);
}

function insertEmployee(d, row) {
  d.employee.push({ employees_id: d.nextId.employee++, ...row });
  // Mirrors the MySQL trigger `new_employee_trigger`: every new employee
  // gets a payroll row with a pay rate of 0.
  d.payroll.push({ employees_uid: row.employees_uid, employees_company: row.employees_company, pay_amount: 0 });
}

export function addEmployee(row) {
  insertEmployee(db(), row);
  save();
}

export function addUser(row) {
  const d = db();
  d.users.push({ users_id: d.nextId.users++, ...row });
  save();
}

export function payRate(uid) {
  return Number(db().payroll.find((p) => p.employees_uid === uid)?.pay_amount ?? 0);
}

export function setPayRate(uid, amount) {
  const row = db().payroll.find((p) => p.employees_uid === uid);
  if (row) row.pay_amount = amount;
  save();
}

export function tracksFor(uid) {
  return db()
    .time_tracking.filter((t) => t.username === uid)
    .sort((a, b) => a.start_time - b.start_time);
}

export function mostRecentTrack(uid) {
  const t = tracksFor(uid);
  return t[t.length - 1] ?? null;
}

export function clockIn(uid) {
  const d = db();
  d.time_tracking.push({ id: d.nextId.time_tracking++, username: uid, start_time: Date.now(), end_time: null, elapsed_time: null });
  save();
}

export function clockOut(uid) {
  const t = mostRecentTrack(uid);
  if (!t || t.elapsed_time !== null) return;
  t.end_time = Date.now();
  t.elapsed_time = Math.round((t.end_time - t.start_time) / 1000);
  save();
}

// SELECT FLOOR(SUM(elapsed_time / 3600)) ... WHERE end_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
export function hoursPast7Days(uid) {
  const since = new Date();
  since.setHours(0, 0, 0, 0);
  since.setDate(since.getDate() - 7);
  const secs = tracksFor(uid)
    .filter((t) => t.elapsed_time !== null && t.end_time >= since.getTime())
    .reduce((sum, t) => sum + t.elapsed_time, 0);
  return Math.floor(secs / 3600);
}

// Pay report: completed shifts between two dates (inclusive), whole hours x rate.
export function payReport(uid, startDate, endDate) {
  const from = new Date(startDate + "T00:00:00").getTime();
  const to = new Date(endDate + "T23:59:59").getTime();
  const secs = tracksFor(uid)
    .filter((t) => t.elapsed_time !== null && t.start_time >= from && t.end_time <= to)
    .reduce((sum, t) => sum + t.elapsed_time, 0);
  const hours = Math.floor(secs / 3600);
  const rate = payRate(uid);
  return { hours, rate, total: hours * rate };
}

export function announcementsFor(company) {
  const today = toDateInput(new Date());
  // The original used a weekly MySQL EVENT to delete expired announcements;
  // here they are simply hidden once their expiration date has passed.
  return db()
    .announcements.filter((a) => a.company === company && (!a.expiration_date || a.expiration_date >= today))
    .sort((a, b) => a.date_posted - b.date_posted);
}

export function addAnnouncement(company, text, expiration) {
  const d = db();
  d.announcements.push({
    id: d.nextId.announcements++,
    announcement_text: text,
    date_posted: Date.now(),
    expiration_date: expiration || null,
    company,
  });
  save();
}

export function companyTaken(company) {
  const c = company.trim().toLowerCase();
  return db().users.some((u) => u.users_company.trim().toLowerCase() === c);
}

export function setUserPassword(type, uid, hashed) {
  if (type === "manager") findUser(uid).users_pwd = hashed;
  else findEmployee(uid).employees_pwd = hashed;
  save();
}

// ---------- formatting (matches the PHP date() formats the app used) ----------

const MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const pad = (n) => String(n).padStart(2, "0");

export function toDateInput(d) {
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
}

function time12(d, upper) {
  const h = d.getHours() % 12 || 12;
  const ap = d.getHours() < 12 ? "am" : "pm";
  return `${h}:${pad(d.getMinutes())} ${upper ? ap.toUpperCase() : ap}`;
}

export const fmt = {
  // "F j, Y"
  longDate: (ms) => {
    const d = new Date(ms);
    return `${MONTHS[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
  },
  // "F j, Y, g:i a"
  longDateTime: (ms) => `${fmt.longDate(ms)}, ${time12(new Date(ms), false)}`,
  // "F j, Y H:i:s"
  longDate24: (ms) => {
    const d = new Date(ms);
    return `${fmt.longDate(ms)} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
  },
  // "Y-m-d"
  ymd: (ms) => toDateInput(new Date(ms)),
  // "g:i A"
  clock: (ms) => time12(new Date(ms), true),
  // "Y-m-d H:i:s"
  sql: (ms) => {
    const d = new Date(ms);
    return `${toDateInput(d)} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
  },
  // sprintf("%02d:%02d:%02d")
  hms: (secs) => `${pad(Math.floor(secs / 3600))}:${pad(Math.floor((secs % 3600) / 60))}:${pad(secs % 60)}`,
  money: (n) => Number(n).toFixed(2),
};
