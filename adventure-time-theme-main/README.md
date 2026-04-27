# Adventure Time Theme — Developer Portfolio

A minimal, dark-themed developer portfolio WordPress theme built with **ACF (Free)** and **Custom Post Types**. Features a responsive design with scroll animations, project filtering, and a clean card-based layout.

## Requirements

- [Local by Flywheel (LocalWP)](https://localwp.com/) — for local development
- WordPress 6.0+
- [Advanced Custom Fields (Free)](https://wordpress.org/plugins/advanced-custom-fields/) plugin
- (Optional) Contact Form 7 for the contact page

## Local Development Setup with LocalWP

### 1. Install LocalWP
Download and install [LocalWP](https://localwp.com/) for your operating system (Mac, Windows, or Linux).

### 2. Create a New Site
1. Open LocalWP and click **"Create a new site"**
2. Choose **"Create a new site"** (not from Blueprint)
3. Enter a site name (e.g., `portfolio-theme`)
4. Choose your environment:
   - **PHP Version:** 8.1+ (recommended)
   - **Web Server:** Apache (recommended for WordPress)
   - **Database:** MySQL 8.0+
5. Set your WordPress admin username and password
6. Click **"Add Site"** and wait for it to finish

### 3. Install the Theme
**Option A — Clone from GitHub:**
```bash
cd ~/Local\ Sites/portfolio-theme/app/public/wp-content/themes/
git clone https://github.com/Dwight123-crypto/adventure-time-theme.git
```

**Option B — Manual install:**
1. Download the theme ZIP from GitHub
2. In LocalWP, click **"Open site folder"** → navigate to `app/public/wp-content/themes/`
3. Extract the `adventure-time-theme` folder there

### 4. Activate the Theme
1. In LocalWP, click **"WP Admin"** to open the WordPress dashboard
2. Go to **Appearance → Themes**
3. Find **Adventure Time Theme** and click **Activate**

### 5. Install Required Plugins
1. Go to **Plugins → Add New**
2. Search for **"Advanced Custom Fields"** and install/activate it
3. (Optional) Search for **"Contact Form 7"** and install/activate it

### 6. Import ACF Field Groups
The theme includes pre-configured ACF JSON files in the `acf-json/` folder. These will auto-sync when you activate the theme:
1. Go to **Custom Fields → Field Groups**
2. You should see the 6 field groups already loaded (Homepage, About, Work, Skill, Certificate, Contact)
3. If they don't appear, go to **Custom Fields → Tools → Import** and import the JSON files from the `acf-json/` folder

### 7. Create Pages
Create these pages in WordPress:

| Page          | Page Template        |
|---------------|----------------------|
| **Home**      | (set as Front Page)  |
| **About**     | About Page           |
| **Contact**   | Contact Page         |

Then go to **Settings → Reading** and set "Home" as your static front page.

### 8. Set Up Navigation Menu
Go to **Appearance → Menus** and create a Primary Menu with:
- Home (link to front page)
- About (link to About page)
- Works (link to Works archive: `/works/`)
- Skills (link to Skills archive: `/skills/`)
- Certificates (link to Certificates archive: `/certificates/`)
- Contact (link to Contact page)

Assign it to the **Primary Navigation** location.

### 9. Add Content

**Works** (Dashboard → Works → Add New)
- Title, featured image, excerpt, and content
- Fill in ACF fields: client, date, live URL, GitHub URL, technologies (one per line), up to 3 gallery images
- Assign a Work Category

**Skills** (Dashboard → Skills → Add New)
- Title + optional featured image
- Fill in ACF fields: icon, proficiency level, percentage, description, display order
- Assign a Skill Category (e.g., Frontend, Backend, Tools)

**Certificates** (Dashboard → Certificates → Add New)
- Title + featured image
- Fill in ACF fields: issuer, date earned, credential ID, verification URL, certificate image

## Features

- **Dark Theme** — Clean dark color scheme with CSS custom properties for easy customization
- **Responsive Design** — Mobile-first layout that works on all screen sizes
- **Custom Post Types** — Works, Skills, and Certificates with dedicated archive and single templates
- **ACF Integration** — All content is editable through custom fields (free ACF, no Pro required)
- **ACF JSON Sync** — Field groups are version-controlled via `acf-json/` for easy deployment
- **Scroll Animations** — Elements animate into view as you scroll using Intersection Observer
- **Project Filtering** — Filter works by category on the archive page
- **Skills Grouped by Category** — Skills archive organizes entries by their taxonomy terms
- **Hero Section** — Homepage hero with greeting, name, tagline, photo, social links, and CTA
- **About Page** — Profile photo, bio, resume download, and stats counters
- **Contact Page** — Contact info display with optional Contact Form 7 integration
- **Navigation** — Responsive nav with mobile hamburger menu and smooth scroll

## Theme Structure

```
adventure-time-theme/
├── acf-json/                   # ACF field group JSON (auto-synced)
│   ├── group_about.json
│   ├── group_certificate.json
│   ├── group_contact.json
│   ├── group_homepage.json
│   ├── group_skill.json
│   └── group_work.json
├── assets/
│   ├── css/main.css            # All styles (dark theme, responsive)
│   ├── js/main.js              # Nav toggle, scroll reveal, filter
│   └── images/                 # Theme images
├── inc/
│   ├── custom-post-types.php   # Works, Skills, Certificates CPTs
│   ├── enqueue.php             # Asset loading + Google Fonts
│   ├── helpers.php             # Utility functions
│   └── theme-setup.php         # Menus, thumbnails, theme supports
├── template-parts/
│   ├── card-work.php           # Work card component
│   ├── card-skill.php          # Skill card component
│   └── card-certificate.php    # Certificate card component
├── templates/
│   ├── page-about.php          # About page template
│   └── page-contact.php        # Contact page template
├── archive-work.php            # Works archive (with category filter)
├── archive-skill.php           # Skills archive (grouped by category)
├── archive-certificate.php     # Certificates archive
├── single-work.php             # Single work detail page
├── front-page.php              # Homepage / hero section
├── header.php                  # Site header + navigation
├── footer.php                  # Site footer
├── index.php                   # Fallback template
├── page.php                    # Default page template
├── functions.php               # Theme functions + includes
├── style.css                   # Theme header (metadata)
├── screenshot.png              # Theme screenshot
└── README.md
```

## ACF Field Groups Reference

The theme uses 6 ACF field groups. These are auto-loaded from the `acf-json/` folder, but if you need to recreate them manually, use the exact **Field Name** values below.

<details>
<summary><strong>Field Group 1: Homepage Settings</strong></summary>

**Location Rule:** Page Type → is equal to → Front Page

| Field Label        | Field Name        | Field Type   | Notes                              |
|--------------------|-------------------|--------------|------------------------------------|
| Greeting Text      | `hero_greeting`   | Text         | Default: "Hello, I'm"             |
| Your Name          | `hero_name`       | Text         |                                    |
| Tagline / Role     | `hero_tagline`    | Text         |                                    |
| Short Description  | `hero_description`| Textarea     | Rows: 3                           |
| Hero Image / Photo | `hero_image`      | Image        | Return Format: Image Array         |
| CTA Button Text    | `hero_cta_text`   | Text         | Default: "View My Work"           |
| CTA Button Link    | `hero_cta_link`   | URL          |                                    |
| GitHub URL         | `social_github`   | URL          |                                    |
| LinkedIn URL       | `social_linkedin` | URL          |                                    |
| Email Address      | `social_email`    | Email        |                                    |
| Twitter / X URL    | `social_twitter`  | URL          |                                    |
</details>

<details>
<summary><strong>Field Group 2: About Page Settings</strong></summary>

**Location Rule:** Page Template → is equal to → About Page

| Field Label          | Field Name             | Field Type | Notes                              |
|----------------------|------------------------|------------|------------------------------------|
| Profile Photo        | `about_photo`          | Image      | Return Format: Image Array         |
| Bio / Long Desc      | `about_bio`            | WYSIWYG    | Media Upload: No                   |
| Resume / CV (PDF)    | `about_resume_file`    | File       | Return Format: File URL, MIME: pdf |
| Years of Experience  | `about_years_exp`      | Number     | Default: 0                         |
| Projects Completed   | `about_projects_count` | Number     | Default: 0                         |
| Happy Clients        | `about_clients_count`  | Number     | Default: 0                         |
</details>

<details>
<summary><strong>Field Group 3: Work Details</strong></summary>

**Location Rule:** Post Type → is equal to → Work

| Field Label        | Field Name          | Field Type  | Notes                                                |
|--------------------|---------------------|-------------|------------------------------------------------------|
| Client Name        | `work_client`       | Text        |                                                      |
| Project Date       | `work_date`         | Date Picker | Display Format: F Y, Return Format: F Y              |
| Live URL           | `work_url`          | URL         |                                                      |
| GitHub Repo URL    | `work_github_url`   | URL         |                                                      |
| Technologies Used  | `work_technologies` | Textarea    | Rows: 4, Instructions: "One technology per line"     |
| Gallery Image 1    | `work_gallery_1`    | Image       | Return Format: Image Array                           |
| Gallery Image 2    | `work_gallery_2`    | Image       | Return Format: Image Array                           |
| Gallery Image 3    | `work_gallery_3`    | Image       | Return Format: Image Array                           |
</details>

<details>
<summary><strong>Field Group 4: Skill Details</strong></summary>

**Location Rule:** Post Type → is equal to → Skill

| Field Label                    | Field Name          | Field Type | Notes                                                            |
|--------------------------------|---------------------|------------|------------------------------------------------------------------|
| Skill Icon (SVG or Image)      | `skill_icon`        | Image      | Return Format: Image Array                                       |
| Proficiency Level              | `skill_proficiency` | Select     | Choices: beginner, intermediate, advanced, expert                |
| Proficiency % (for bar)        | `skill_percentage`  | Number     | Min: 0, Max: 100, Default: 50                                   |
| Short Description              | `skill_description` | Textarea   | Rows: 2                                                         |
| Display Order                  | `skill_order`       | Number     | Default: 0, Instructions: "Lower = shows first"                 |
</details>

<details>
<summary><strong>Field Group 5: Certificate Details</strong></summary>

**Location Rule:** Post Type → is equal to → Certificate

| Field Label            | Field Name            | Field Type  | Notes                               |
|------------------------|-----------------------|-------------|-------------------------------------|
| Issuing Organization   | `cert_issuer`         | Text        |                                     |
| Date Earned            | `cert_date`           | Date Picker | Display Format: F Y, Return: F Y   |
| Credential ID          | `cert_credential_id`  | Text        |                                     |
| Verification URL       | `cert_url`            | URL         |                                     |
| Certificate Image      | `cert_image`          | Image       | Return Format: Image Array          |
</details>

<details>
<summary><strong>Field Group 6: Contact Page Settings</strong></summary>

**Location Rule:** Page Template → is equal to → Contact Page

| Field Label                | Field Name                 | Field Type | Notes                                            |
|----------------------------|----------------------------|------------|--------------------------------------------------|
| Section Heading            | `contact_heading`          | Text       | Default: "Get In Touch"                          |
| Description                | `contact_description`      | Textarea   | Rows: 3                                          |
| Contact Email              | `contact_email`            | Email      |                                                  |
| Phone Number               | `contact_phone`            | Text       |                                                  |
| Location                   | `contact_location`         | Text       |                                                  |
| Contact Form Shortcode     | `contact_form_shortcode`   | Text       | Instructions: "Paste CF7 or WPForms shortcode"   |
</details>

## Free ACF Workarounds (No Repeater)

Since the free ACF plugin doesn't include the Repeater field:

- **Technologies**: Stored as a Textarea (one item per line), parsed via `devportfolio_parse_lines()` helper
- **Gallery Images**: Individual image fields (`work_gallery_1`, `work_gallery_2`, `work_gallery_3`) collected via `devportfolio_get_work_gallery()`
- **Skills list**: Each skill is its own CPT post (rather than repeater rows on a page)
- **Certificates**: Same approach — each cert is its own post

## Customization

- **Colors/Fonts**: Edit CSS variables in `assets/css/main.css` (`:root` block)
- **Google Fonts**: Change in `inc/enqueue.php`
- **Image sizes**: Modify in `inc/theme-setup.php`
- **ACF fields**: Add/modify via Custom Fields → Field Groups in the WordPress dashboard

## Changelog

### v1.0.0 — Initial Release
- Dark-themed developer portfolio WordPress theme
- 3 Custom Post Types: Works, Skills, Certificates
- Custom taxonomies: Work Category, Skill Category
- Homepage hero section with ACF fields (greeting, name, tagline, photo, social links, CTA)
- About page template with profile photo, bio, resume download, and stats
- Contact page template with Contact Form 7 support
- Works archive with category filtering via JavaScript
- Skills archive grouped by Skill Category
- Certificates archive with card layout
- Single work detail page with gallery, technologies, and project links
- Responsive mobile navigation with hamburger menu
- Scroll-reveal animations using Intersection Observer API
- ACF JSON sync for version-controlled field groups
- Google Fonts integration (Inter + Fira Code)
- CSS custom properties for easy theming
- Screenshot included for theme preview
