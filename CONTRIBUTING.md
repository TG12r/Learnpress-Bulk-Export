# Contributing to LearnPress Bulk Export

First off, thank you for considering contributing to LearnPress Bulk Export!

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When you create a bug report, include as many details as possible:

**Bug Report Template:**
```markdown
**Description:**
A clear description of the bug

**Steps to Reproduce:**
1. Go to '...'
2. Click on '...'
3. See error

**Expected Behavior:**
What you expected to happen

**Actual Behavior:**
What actually happened

**Environment:**
- WordPress version:
- LearnPress version:
- PHP version:
- Plugin version:
- Browser (if relevant):

**Screenshots:**
If applicable, add screenshots

**Error Logs:**
Any relevant error messages from debug.log
```

### Suggesting Features

Feature requests are welcome! Please provide:
- Clear description of the feature
- Why it would be useful
- Possible implementation approach (optional)

### Pull Requests

1. **Fork the repo** and create your branch from `main`
2. **Follow WordPress coding standards**
3. **Test your changes** thoroughly
4. **Update documentation** if needed
5. **Write clear commit messages**

#### Pull Request Template:
```markdown
**What does this PR do?**
Brief description

**Related Issue:**
Fixes #(issue number)

**Changes Made:**
- Change 1
- Change 2

**Testing:**
How you tested the changes

**Screenshots:** (if applicable)
```

## Development Setup

```bash
# Clone the repository
git clone https://github.com/TG12r/Learnpress-Bulk-Export.git

# Install in WordPress
cp -r Learnpress-Bulk-Export /path/to/wordpress/wp-content/plugins/

```

## Coding Standards

- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use WordPress coding conventions for JavaScript
- Add PHPDoc blocks for all functions
- Use meaningful variable names
- Keep functions focused and small

## Testing Checklist

Before submitting a PR, ensure:
- [ ] Code follows WordPress coding standards
- [ ] No PHP errors or warnings
- [ ] Works with latest WordPress version
- [ ] Works with LearnPress 4.0+
- [ ] All features work as expected
- [ ] No console errors in browser
- [ ] PDF generation works correctly

## Questions?

Feel free to open an issue or discussion if you have questions!

Thank you for contributing! 🎉
