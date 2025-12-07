# LearnPress Bulk Export

> Bulk PDF reports for LearnPress student progress.

[![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)](https://wordpress.org/)
[![LearnPress](https://img.shields.io/badge/LearnPress-4.0%2B-orange.svg)](https://wordpress.org/plugins/learnpress/)
[![License](https://img.shields.io/badge/license-GPL--2.0%2B-green.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net)

---

## What it does

Generate professional PDF reports for multiple LearnPress students at once. Includes donut charts, progress bars, enrollment dates, and optional course comparison tables.

**Key features:**
- Dashboard-style PDFs with visual progress tracking
- Bulk selection and course filtering
- Direct email delivery to students
- Course comparison for shared courses (2+ students)
- Debug mode for troubleshooting

---

## Requirements

- WordPress 5.0+
- LearnPress 4.0+
- PHP 7.4+

---

## Install

1. Download the [latest release](../../releases)
2. Upload via WordPress Admin → Plugins → Add New
3. Activate

Or clone directly:
```bash
cd wp-content/plugins
git clone https://github.com/YOUR-USERNAME/Learnpress-Bulk-Export.git
```

---

## Usage

1. Navigate to **LearnPress → Bulk Export**
2. Search and select students
3. Click "View Courses" to choose which courses to include
4. Click **Export PDF** or **Send Email to Students**

**Pro tips:**
- Press Enter to search
- Use "Clear All" to reset selections
- Enable "Debug Mode" if something breaks

---

## FAQ

**Q: Can I customize the PDF design?**  
A: Yes. Edit `includes/class-lp-bulk-export-ajax.php`

**Q: What if a student has no courses?**  
A: PDF shows "No courses enrolled yet"

**Q: Timeout on large exports?**  
A: Increase PHP `max_execution_time` and `memory_limit`

**Q: Unicode issues?**  
A: Plugin uses iconv with TRANSLIT flag. Some special characters may not render perfectly.

[More questions? Open an issue](../../issues)

---

## Development

```
learnpress-bulk-export/
├── admin/              # UI and styles
├── assets/js/          # Frontend JavaScript
├── includes/           # PHP logic and PDF generation
│   ├── class-lp-bulk-export-ajax.php  # Core functionality
│   └── fpdf/           # PDF library
└── learnpress-bulk-export.php  # Plugin entry
```

**Contributing:**  
PRs welcome. Follow WordPress coding standards. No 500-line functions without comments.

See [CONTRIBUTING.md](CONTRIBUTING.md) for details.

---

## Known Issues

- Large PDFs (100+ pages) may timeout on low-resource servers
- Some UTF-8 characters render imperfectly (FPDF limitation)

---

## License

GPL-2.0-or-later

---

## Support

- [Issues](../../issues)
- [Discussions](../../discussions)

---

<p align="center">Made with ❤️ for the LearnPress community</p>
