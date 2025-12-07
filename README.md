# 📊 LearnPress Bulk Export

> **Professional PDF reporting for LearnPress student progress**

Transform your LearnPress course data into beautiful, dashboard-style PDF reports with visual progress tracking, enrollment analytics, and course comparison tables.

[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![LearnPress](https://img.shields.io/badge/LearnPress-4.0%2B-orange.svg)](https://wordpress.org/plugins/learnpress/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-green.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net)

---

## ✨ Features

### 📑 **Professional PDF Reports**
- **Dashboard-style design** with modern cards and visual elements
- **Donut charts** showing completion percentage for each course
- **Progress bars** with status-based color coding (enrolled, completed, finished)
- **Enrollment & completion dates** with customizable date formats
- **Item completion tracking** (e.g., "15/20 items completed")

### 👥 **Bulk Processing**
- Select **multiple students** at once
- Choose **specific courses** per student
- Generate individual PDF pages for each student
- Email PDFs directly to students with one click

### 📈 **Course Comparison Table** (Optional)
- Automatically groups courses with 2+ students
- Side-by-side progress comparison
- Displays completion rates, items completed, and status
- Toggle on/off with checkbox

### 🎯 **User-Friendly Interface**
- **Real-time search** for students (by name or email)
- **Loading indicators** for better UX
- **Enter key support** for quick searches
- **"Clear All" button** to reset selections
- **Visual feedback** with active states and spinners

### 🔧 **Developer-Friendly**
- **Debug mode** with detailed logging
- Clean, well-documented code
- WordPress coding standards compliant
- Extensible architecture

---

## 📋 Requirements

- **WordPress:** 5.0 or higher
- **PHP:** 7.4 or higher
- **LearnPress:** 4.0 or higher
- **Memory:** 128MB minimum (256MB recommended for large exports)

---

## 🚀 Installation

### Method 1: WordPress Admin (Recommended)

1. Download the latest release from [Releases](../../releases)
2. Navigate to `WordPress Admin → Plugins → Add New → Upload Plugin`
3. Choose the downloaded `.zip` file
4. Click **Install Now** and then **Activate**

### Method 2: Manual Installation

1. Download and extract the plugin files
2. Upload the `learnpress-bulk-export` folder to `/wp-content/plugins/`
3. Navigate to `WordPress Admin → Plugins`
4. Activate **LearnPress Bulk Export**

### Method 3: Git Clone (For Developers)

```bash
cd wp-content/plugins
git clone https://github.com/TG12r/Learnpress-Bulk-Export.git learnpress-bulk-export
```

Then activate via WordPress admin.

---

## 📖 Usage

### Generating PDF Reports

1. **Navigate to the plugin page:**
   - Go to `WordPress Admin → LP Bulk Export` (On the left menu)

2. **Search and select students:**
   - Enter student name or email in the search field
   - Press **Enter** or click **Search**
   - Click **Add** next to each student you want to include

3. **Select courses:**
   - Click **View Courses** next to a selected student
   - Check/uncheck courses to include in their report
   - Repeat for each student

4. **Configure options:**
   - ✅ **Include Course Comparison Table** - Shows courses with 2+ students
   - ⚙️ **Debug Mode** - Displays detailed logs (for troubleshooting)

5. **Generate PDF:**
   - Click **Export PDF** to download the report
   - Or click **Send Email to Students** to email individual reports

### Features Explained

#### Search Functionality
- Type at least 3 characters
- Press **Enter** for quick search
- Results show name and email

#### Course Selection
- Each student can have different courses selected
- Use **Select All** to quickly check/uncheck
- Courses show current status and completion

#### Clear All Button
- Appears when you have selected users
- Shows count: "Clear All (3)"
- Asks for confirmation before clearing

---

## 🎨 PDF Report Breakdown

### User Card
- Student name (large, bold)
- Email address
- Clean card design with shadow effects

### Course Cards (Per Course)
- **Course title** (truncated if too long)
- **Status pill** (color-coded: blue for enrolled, green for completed)
- **Donut chart** (visual progress indicator)
- **Progress bar** (matches status color)
- **Enrollment date** (if available)
- **Completion date** (if finished)
- **Items completed** (e.g., "15 / 20 items completed")

### Comparison Table (Optional)
- Groups courses shared by 2+ students
- Displays: Student name, Progress %, Items completed, Status
- Alternating row colors for readability
- Automatically hidden if checkbox is unchecked

---

## ❓ FAQ

### Q: Can I customize the PDF design?
**A:** Yes! The PDF generation code is in `includes/class-lp-bulk-export-ajax.php`. You can modify colors, fonts, layout, and add custom branding.

### Q: What if a student has no enrolled courses?
**A:** The PDF will display a message: "No courses enrolled yet."

### Q: Can I email PDFs to specific addresses?
**A:** Currently, emails are sent to the student's registered email. You can modify the `send_email()` method to customize recipients.

### Q: How do I troubleshoot PDF generation issues?
**A:** Enable **Debug Mode** checkbox before generating. You'll see detailed logs about what's happening during PDF creation.

### Q: Is there a limit on how many students I can export at once?
**A:** The limit depends on your server's PHP memory and execution time. For large batches (50+ students), consider increasing `memory_limit` and `max_execution_time` in `php.ini`.

### Q: Can I translate the plugin?
**A:** The plugin uses WordPress text domains. You can use tools like Poedit to create translations for your language.

---

## 🐛 Known Issues

- **Large PDFs (100+ pages):** May timeout on servers with low `max_execution_time`. Recommended: 300 seconds or higher.
- **UTF-8 Characters:** Some special characters may not render correctly. The plugin uses `iconv()` for conversion with `//TRANSLIT` flag.

---

## 🛠️ Development

### Project Structure

```
learnpress-bulk-export/
├── admin/
│   ├── admin-page.php          # Main admin interface HTML
│   └── css/
│       └── admin.css           # Admin styles
├── assets/
│   └── js/
│       └── admin.js            # Frontend JavaScript (AJAX, UI logic)
├── includes/
│   ├── class-lp-bulk-export.php           # Main plugin class
│   ├── class-lp-bulk-export-loader.php    # Hooks and filters loader
│   ├── class-lp-bulk-export-ajax.php      # AJAX handlers & PDF generation
│   └── fpdf/                              # FPDF library
│       ├── fpdf.php
│       └── font/                          # Font definitions
├── learnpress-bulk-export.php  # Plugin entry point
└── README.md
```

### Making Changes

1. **Fork the repository**
2. **Create a feature branch:** `git checkout -b feature/AmazingFeature`
3. **Commit your changes:** `git commit -m 'Add some AmazingFeature'`
4. **Push to the branch:** `git push origin feature/AmazingFeature`
5. **Open a Pull Request**

### Coding Standards

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- Use descriptive variable and function names
- Add PHPDoc comments for functions and classes
- Test with WordPress debug mode enabled

---

## 📝 Changelog

### Version 1.0.0 (Initial Release)
- ✨ PDF report generation with dashboard design
- ✨ Bulk student selection
- ✨ Course-specific selection per student
- ✨ Email delivery functionality
- ✨ Course comparison table (optional)
- ✨ Debug mode with detailed logs
- ✨ Loading indicators and UX improvements
- ✨ Enter key support for search
- ✨ Clear All button for selected users

---

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

- 🐛 **Report bugs** by opening an issue
- 💡 **Suggest features** through feature requests
- 📖 **Improve documentation** with clearer examples
- 🔧 **Submit pull requests** with bug fixes or new features
- 🌍 **Translate** the plugin into your language

Please read our [Contributing Guidelines](CONTRIBUTING.md) before submitting PRs.

---

## 📄 License

This project is licensed under the **GNU General Public License v2.0 or later** - see the [LICENSE](LICENSE) file for details.

---

## 💬 Support

- **Issues:** [GitHub Issues](../../issues)
- **Discussions:** [GitHub Discussions](../../discussions)

---

## 🙏 Credits

### Built With
- [FPDF](http://www.fpdf.org/) - PDF generation library
- [LearnPress](https://wordpress.org/plugins/learnpress/) - LMS for WordPress
- [WordPress](https://wordpress.org/) - Content management system

---

## ⭐ Show Your Support

If this plugin helped you, please:
- Give it a ⭐ on GitHub
- Share it with your network
- Consider [sponsoring the project](https://github.com/sponsors/TG12r)

---

<p align="center">
  Made with ❤️
</p>

<p align="center">
  <a href="../../issues">Report Bug</a> •
  <a href="../../issues">Request Feature</a> •
</p>
