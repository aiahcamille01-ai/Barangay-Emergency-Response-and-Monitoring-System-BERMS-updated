# BERMS - Barangay 171 Emergency Response System
## Project Structure Documentation

### 📁 Folder Organization

```
barangay171emergencyresponseandmonitoringsystem/
├── assets/                          # Static assets (CSS, JS, images)
│   ├── css/
│   │   └── main.css                # Consolidated stylesheet (merged from berms.css, style.css)
│   └── js/
│       └── main.js                 # Main JavaScript (can consolidate script.js here)
│
├── pages/                           # User-facing pages
│   ├── homepage.php
│   ├── userpage1.php
│   ├── reportform.php
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── admin/                           # Admin dashboard and management
│   ├── admin_dashboard.php
│   ├── adminlogin.php
│   ├── registeradmin.php
│   ├── get_report.php
│   ├── get_all_reports.php
│   └── delete_report.php
│
├── config/                          # Configuration files
│   └── connect.php                 # Database connection
│
├── database/                        # Database related files
│   └── incident_system.sql         # Database schema
│
├── uploads/                         # All uploads organized by type
│   ├── reports/                    # Emergency report attachments (replaces report_uploads/)
│   ├── admins/                     # Admin ID pictures (replaces admin_uploads/)
│   └── users/                      # User uploads
│
├── images/                          # Project images and logos
│
├── index.php                        # Entry point / homepage router
├── package.json                     # Node dependencies (Tailwind)
├── tailwind.config.js              # Tailwind configuration
├── tailwind.css                    # Tailwind output (keep for now)
└── .gitignore                      # Git ignore file
```

### 📋 File Changes & Deprecations

#### Consolidated/Removed:
- **berms.css** → Merged into `assets/css/main.css`
- **style.css** → Merged into `assets/css/main.css`
- **report_uploads/** → Moved to `uploads/reports/`
- **admin_uploads/** → Moved to `uploads/admins/`
- **uploads/** → Converted to parent folder for organized uploads

#### Kept but Reorganized:
- **script.js** → Can be moved to `assets/js/main.js` (update references as needed)
- **create_dummy_images.php** → Available if needed for development
- **TAILWIND_SETUP.md** → Documentation reference

### 🔄 Update File References

If you move files, remember to update references in your PHP files:

```php
// Old reference
<link rel="stylesheet" href="berms.css">

// New reference
<link rel="stylesheet" href="assets/css/main.css">
```

### 🚀 Next Steps

1. **Update CSS references** in all HTML/PHP files to use `assets/css/main.css`
2. **Move image files** to `images/` folder
3. **Update upload paths** in PHP files:
   - `report_uploads/` → `uploads/reports/`
   - `admin_uploads/` → `uploads/admins/`
4. **Consolidate JavaScript** into `assets/js/main.js` if desired
5. **Move utility files** to development/scripts folder if you want to keep them

### 📝 Notes

- Tailwind CSS is still configured and can be removed if moving to pure custom CSS
- The structure now separates concerns for better maintainability
- All upload paths should be relative to project root
- Consider adding .htaccess or index.php routing to control access to `/uploads` folders
