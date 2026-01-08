# 🔍 Debugging Workflow - دليل استكشاف الأخطاء

## 📋 نظرة عامة

هذا الدليل يغطي كل مرحلة من مراحل النشر والـ deployment، من Git المحلي حتى ظهور الموقع على السيرفر.

---

## 1️⃣ Git (Local) - قبل الـ Push

### ما الذي يمكن أن يخطئ؟

| المشكلة | السبب المحتمل | كيف أكتشفها | الحل |
|---------|---------------|-------------|------|
| ملفات غير متتبعة | `.gitignore` يمنع الملفات | `git status` | أضف الملفات يدوياً أو عدّل `.gitignore` |
| تغييرات غير محفوظة | نسيان `git add` | `git status` | `git add .` |
| Commit message خاطئ | خطأ إملائي | `git log --oneline -1` | `git commit --amend -m "رسالة صحيحة" |
| Branch خاطئ | العمل على branch غير صحيح | `git branch` | `git checkout main` |

### الأوامر الأساسية:

```bash
# 1. تحقق من الحالة
git status

# 2. تحقق من التغييرات
git diff

# 3. تحقق من آخر commit
git log --oneline -5

# 4. تحقق من Branch الحالي
git branch

# 5. تحقق من Remote
git remote -v
```

### مثال عملي:

```bash
# ❌ الخطأ: "nothing to commit, working tree clean"
# لكن الملفات موجودة

# ✅ الحل:
git status                    # يظهر الملفات غير المتتبعة
git add .                     # أضف الملفات
git commit -m "fix: add missing files"
```

---

## 2️⃣ GitHub (Push / Branches / Webhooks)

### ما الذي يمكن أن يخطئ؟

| المشكلة | السبب المحتمل | كيف أكتشفها | الحل |
|---------|---------------|-------------|------|
| Push فشل | Authentication | `git push origin main` | تحقق من GitHub token |
| Branch غير موجود | Branch محلي فقط | GitHub → Branches | `git push -u origin main` |
| Webhook لا يعمل | URL خاطئ أو معطل | GitHub → Settings → Webhooks | تحقق من Payload URL |
| Conflict | تغييرات على GitHub | `git pull` | `git pull --rebase` أو `git merge` |

### الأماكن للفحص:

1. **GitHub Repository:**
   - `https://github.com/USERNAME/REPO` → Commits
   - `Settings` → `Webhooks` → تحقق من Status (✅ أو ❌)
   - `Actions` → تحقق من أي workflows

2. **الأوامر:**

```bash
# 1. تحقق من آخر push
git log origin/main --oneline -5

# 2. تحقق من الفرق بين local و remote
git fetch
git log HEAD..origin/main

# 3. اختبار push بدون تغيير
git push --dry-run origin main
```

### مثال عملي:

```bash
# ❌ الخطأ: "remote rejected"
# السبب: تغييرات على GitHub لم تسحبها

# ✅ الحل:
git fetch origin
git pull origin main --rebase
git push origin main
```

---

## 3️⃣ Webhook Delivery - تسليم Webhook

### ما الذي يمكن أن يخطئ؟

| المشكلة | السبب المحتمل | كيف أكتشفها | الحل |
|---------|---------------|-------------|------|
| Webhook لا يصل | URL خاطئ | GitHub → Webhooks → Recent Deliveries | تحقق من URL في Hostinger |
| 404 Error | Path خاطئ | Recent Deliveries → Response | عدّل URL في Hostinger Git |
| Timeout | Server بطيء | Recent Deliveries → Duration | تحقق من Server response time |
| Authentication | Token خاطئ | Recent Deliveries → Response | عدّل Token في Hostinger |

### الأماكن للفحص:

1. **GitHub:**
   - `Settings` → `Webhooks` → `Recent Deliveries`
   - انقر على أي delivery → شاهد:
     - **Request:** ما أرسلته GitHub
     - **Response:** ما رد عليه Hostinger
     - **Duration:** كم استغرق

2. **Hostinger:**
   - `Git` → `Webhook URL` (انسخه)
   - تأكد من أن URL صحيح

### مثال عملي:

```
❌ الخطأ في Recent Deliveries:
Status: 404 Not Found
Response: "Not Found"

✅ الحل:
1. تحقق من Webhook URL في Hostinger
2. تأكد من أن Path صحيح: /public_html
3. اختبر URL يدوياً: curl -X POST WEBHOOK_URL
```

---

## 4️⃣ Hostinger Deployment - النشر على Hostinger

### ما الذي يمكن أن يخطئ؟

| المشكلة | السبب المحتمل | كيف أكتشفها | الحل |
|---------|---------------|-------------|------|
| الملفات لم تصل | Auto Deploy معطل | File Manager → تاريخ الملفات | فعّل Auto Deploy |
| الملفات في مكان خاطئ | Install Path خاطئ | File Manager → المسار | عدّل Install Path |
| Branch خاطئ | Branch غير main | Git Settings → Branch | غيّر إلى main |
| Permission denied | صلاحيات خاطئة | File Manager → Permissions | chmod 755/644 |

### الأماكن للفحص:

1. **Hostinger Control Panel:**
   - `Git` → تحقق من:
     - Repository URL ✅
     - Branch: `main` ✅
     - Install Path: `/public_html` ✅
     - Auto Deploy: Enabled ✅

2. **File Manager:**
   - تحقق من تاريخ تعديل الملفات
   - تحقق من المسار: `/public_html/index.html`
   - تحقق من الصلاحيات

### مثال عملي:

```
❌ المشكلة: الملفات لم تتحدث بعد push

✅ الحل خطوة بخطوة:
1. Hostinger → Git → Recent Deployments
   - إذا لم يكن هناك deployment → Webhook لا يعمل
   
2. File Manager → public_html → index.html
   - Right-click → Properties → Modified Date
   - إذا التاريخ قديم → Deploy لم يحدث
   
3. Hostinger → Git → Manual Deploy
   - اضغط "Deploy Now"
   - إذا نجح → المشكلة في Webhook
   - إذا فشل → تحقق من Logs
```

---

## 5️⃣ File System (public_html / Permissions)

### ما الذي يمكن أن يخطئ؟

| المشكلة | السبب المحتمل | كيف أكتشفها | الحل |
|---------|---------------|-------------|------|
| 403 Forbidden | صلاحيات خاطئة | Browser → 403 Error | chmod 755 للمجلدات، 644 للملفات |
| ملفات غير موجودة | Deploy فشل | File Manager | تحقق من Deploy Logs |
| .htaccess يمنع | قواعد خاطئة | Browser → 403 | عدّل أو احذف .htaccess مؤقتاً |
| مسار خاطئ | Install Path | File Manager → المسار | عدّل Install Path |

### الأوامر (في File Manager):

```
المجلدات: 755
الملفات: 644
الملفات التنفيذية: 755
```

### مثال عملي:

```
❌ الخطأ: 403 Forbidden على index.html

✅ الحل:
1. File Manager → public_html → index.html
2. Right-click → Change Permissions
3. ضع: 644
4. Apply
5. Refresh المتصفح
```

---

## 6️⃣ Server Response (403 / 404 / 500)

### 403 Forbidden

**الأسباب المحتملة:**
- صلاحيات خاطئة
- `.htaccess` يمنع الوصول
- `index.html` غير موجود
- Directory listing معطل

**كيف أكتشف:**
```bash
# في Browser DevTools (F12)
Network tab → Status: 403
Response: "Forbidden"
```

**الحل:**
1. File Manager → Permissions → 644 للملفات
2. احذف `.htaccess` مؤقتاً للاختبار
3. تأكد من وجود `index.html`

---

### 404 Not Found

**الأسباب المحتملة:**
- الملف غير موجود
- مسار خاطئ
- `.htaccess` rewrite rules خاطئة

**كيف أكتشف:**
```bash
# في Browser DevTools
Network tab → Status: 404
Response: "Not Found"
```

**الحل:**
1. File Manager → تحقق من وجود الملف
2. تحقق من المسار في URL
3. تحقق من `.htaccess` rewrite rules

---

### 500 Internal Server Error

**الأسباب المحتملة:**
- خطأ في PHP
- `.htaccess` syntax error
- قاعدة البيانات غير متصلة

**كيف أكتشف:**
```bash
# في Browser DevTools
Network tab → Status: 500
Response: "Internal Server Error"
```

**الحل:**
1. cPanel → Error Logs → اقرأ آخر خطأ
2. تحقق من `.htaccess` syntax
3. تحقق من ملفات PHP (إذا كان هناك)

---

## 7️⃣ Browser (Cache / CDN)

### ما الذي يمكن أن يخطئ؟

| المشكلة | السبب المحتمل | كيف أكتشفها | الحل |
|---------|---------------|-------------|------|
| تغييرات لا تظهر | Browser Cache | Hard Refresh | Ctrl+Shift+R (Windows) / Cmd+Shift+R (Mac) |
| CSS/JS قديم | CDN Cache | Network tab → Size | Clear Cache أو Hard Refresh |
| Service Worker | PWA Cache | DevTools → Application | Unregister Service Worker |

### الأوامر:

```
Hard Refresh:
- Windows: Ctrl + Shift + R
- Mac: Cmd + Shift + R

Clear Cache:
- Chrome: Ctrl + Shift + Delete
- Firefox: Ctrl + Shift + Delete
```

### مثال عملي:

```
❌ المشكلة: التغييرات لا تظهر

✅ الحل:
1. DevTools (F12) → Network tab
2. ✅ Enable "Disable cache"
3. Hard Refresh: Ctrl+Shift+R
4. إذا ظهرت → المشكلة Cache
5. إذا لم تظهر → المشكلة في Server
```

---

## 🎯 Workflow سريع للـ Debugging

```
1. Git Local
   ↓ git status
   
2. GitHub Push
   ↓ git push origin main
   
3. GitHub Webhook
   ↓ Settings → Webhooks → Recent Deliveries
   
4. Hostinger Deploy
   ↓ Git → Recent Deployments
   
5. File System
   ↓ File Manager → تحقق من الملفات
   
6. Server Response
   ↓ Browser → DevTools → Network
   
7. Browser Cache
   ↓ Hard Refresh (Ctrl+Shift+R)
```

---

## 📝 ملاحظات مهمة

1. **ابدأ من الأسفل للأعلى:** Browser → Server → File System → Deploy → Git
2. **استخدم DevTools دائماً:** F12 → Network tab
3. **تحقق من Logs:** cPanel → Error Logs
4. **اختبر بملف بسيط:** `test.html` قبل الملفات المعقدة
5. **احفظ نسخة احتياطية:** قبل أي تغيير كبير
