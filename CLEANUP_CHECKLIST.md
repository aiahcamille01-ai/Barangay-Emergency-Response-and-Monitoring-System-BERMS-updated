# 📋 Project Cleanup Checklist

## ✅ Completed

- [x] Created proper folder structure:
  - `assets/css/` - CSS files
  - `assets/js/` - JavaScript files
  - `pages/` - User-facing pages
  - `admin/` - Admin dashboard
  - `config/` - Configuration files
  - `database/` - Database files
  - `uploads/reports/` - Emergency report uploads
  - `uploads/admins/` - Admin ID pictures
  - `uploads/users/` - User uploads (placeholder)

- [x] **Consolidated CSS files:**
  - ✅ Merged `berms.css` and `style.css` into `assets/css/main.css`
  - ✅ Old CSS files can be deleted (keeping for reference)

## 📝 Still To Do (Recommended)

### Phase 1: Update References
- [ ] Update all PHP files to reference `assets/css/main.css` instead of `berms.css` or `style.css`
- [ ] Update image references to use proper relative paths
- [ ] Consolidate `script.js` into `assets/js/main.js` (optional)

### Phase 2: Reorganize Files (Using CLI/File Manager)
- [ ] Move pages to `pages/` folder:
  - `homepage.php` → `pages/homepage.php`
  - `userpage1.php` → `pages/userpage1.php`
  - `reportform.php` → `pages/reportform.php`
  - `login.php` → `pages/login.php`
  - `register.php` → `pages/register.php`
  - `logout.php` → `pages/logout.php`

- [ ] Move config to `config/` folder:
  - `connect.php` → `config/connect.php`

- [ ] Move database schema to `database/` folder:
  - `incident_system.sql` → `database/incident_system.sql`

- [ ] Update upload paths in PHP files:
  - `report_uploads/` → `uploads/reports/`
  - `admin_uploads/` → `uploads/admins/`

### Phase 3: Cleanup (Optional)
- [ ] Delete old CSS files (after verifying main.css works):
  - `berms.css`
  - `style.css`

- [ ] Archive or remove development files:
  - `create_dummy_images.php` (development utility)
  - `TAILWIND_SETUP.md` (documentation reference)

### Phase 4: Add Security Files
- [ ] Create `.htaccess` in `/uploads` to prevent script execution
- [ ] Create `.htaccess` in `/database` to prevent direct access

---

## 📚 References

See `FOLDER_STRUCTURE.md` for complete folder organization details.

## 💡 Notes

- The `assets/css/main.css` file has been created and consolidated
- Tailwind CSS can stay as-is (it's built into your HTML via CDN anyway)
- Update `include()` and `include_once()` paths in PHP files if you move them
- Make sure to test all functionality after reorganizing

## 🔐 Recommended Security Additions

**Create `/uploads/.htaccess`:**
```
<FilesMatch "\.(php|php3|php4|php5|php6|php7|phtml|pht|phar)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

**Create `/database/.htaccess`:**
```
Order deny,allow
Deny from all
```
