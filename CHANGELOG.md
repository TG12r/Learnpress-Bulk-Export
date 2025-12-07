# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2024-12-07

### Added
- ✨ **PDF Report Generation** with professional dashboard design
  - Donut charts for visual progress representation
  - Color-coded progress bars (blue for enrolled, green for completed)
  - Status pills with dynamic colors
  - Enrollment and completion dates
  - Item completion tracking (e.g., "15/20 items completed")

- 👥 **Bulk User Management**
  - Multi-user selection interface
  - Search functionality with real-time results
  - Individual course selection per user
  - "Clear All" button to reset selections
  - Enter key support for quick search

- 📧 **Email Delivery System**
  - Send individual PDF reports directly to students
  - Bulk email processing
  - Automatic cleanup of temporary files

- 📊 **Course Comparison Table** (Optional)
  - Automatic grouping by course
  - Only shows courses with 2+ students
  - Side-by-side progress comparison
  - Toggle on/off via checkbox

- 🎨 **UI/UX Enhancements**
  - Loading indicators for search and course loading
  - Active state highlighting
  - User name display in course selection panel
  - Improved course item design with left border accent
  - Scrollable course list (max 400px height)

- 🔧 **Developer Features**
  - Debug mode with detailed logging
  - Clean codebase following WordPress standards
  - Extensible architecture
  - Well-documented functions

### Technical Details
- Built on FPDF library for PDF generation
- AJAX-based architecture for smooth UX
- Compatible with WordPress 5.0+
- Requires LearnPress 4.0+
- PHP 7.4+ required

---

## [Unreleased]

### Planned Features
- [ ] Custom PDF templates
- [ ] Multi-language support
- [ ] Advanced filtering options
- [ ] Export to CSV/Excel formats
- [ ] Custom branding options

---

[1.0.0]: https://github.com/TG12r/Learnpress-Bulk-Export/releases/tag/v1.0.0
