---
name: TLC Admin
description: Professional admin dashboard for ecclesiastical organization management
colors:
  brand-primary: "#465fff"
  brand-dark: "#2a31d8"
  blue-light-primary: "#0ba5ec"
  blue-light-dark: "#026aa2"
  gray-neutral-light: "#f9fafb"
  gray-neutral-medium: "#e4e7ec"
  gray-text-primary: "#344054"
  gray-text-secondary: "#667085"
  success-base: "#12b76a"
  error-base: "#f04438"
  warning-base: "#f79009"
  orange-base: "#fb6514"
typography:
  display:
    fontFamily: "Outfit, sans-serif"
    fontSize: "72px"
    fontWeight: 500
    lineHeight: 1.25
    letterSpacing: "normal"
  headline:
    fontFamily: "Outfit, sans-serif"
    fontSize: "48px"
    fontWeight: 500
    lineHeight: 1.2
  title:
    fontFamily: "Outfit, sans-serif"
    fontSize: "30px"
    fontWeight: 600
    lineHeight: 1.3
  body:
    fontFamily: "Outfit, sans-serif"
    fontSize: "14px"
    fontWeight: 400
    lineHeight: 1.5
  label:
    fontFamily: "Outfit, sans-serif"
    fontSize: "12px"
    fontWeight: 600
    lineHeight: 1.5
    letterSpacing: "0.5px"
rounded:
  sm: "4px"
  md: "8px"
  lg: "12px"
  xl: "16px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
components:
  button-primary:
    backgroundColor: "{colors.brand-primary}"
    textColor: "#ffffff"
    padding: "10px 16px"
    rounded: "{rounded.md}"
  button-primary-hover:
    backgroundColor: "{colors.brand-dark}"
  button-secondary:
    backgroundColor: "{colors.gray-neutral-light}"
    textColor: "{colors.gray-text-primary}"
    padding: "10px 16px"
    rounded: "{rounded.md}"
  card:
    backgroundColor: "#ffffff"
    rounded: "{rounded.lg}"
    padding: "20px"
  input:
    backgroundColor: "#ffffff"
    textColor: "{colors.gray-text-primary}"
    rounded: "{rounded.md}"
    padding: "8px 12px"
---

# Design System: TLC Admin

## 1. Overview

**Creative North Star: "The Corporate Dashboard"**

TLC Admin's visual system embodies professional clarity and accessible structure. Designed for administrators, diocese leaders, and nucleo/secretaria managers, the interface conveys organizational confidence while remaining inviting and navigable at all experience levels. The aesthetic draws from corporate software standards (clean sans-serif, restrained color, hierarchical information layout) but softens them with generous whitespace and warm neutral grays, avoiding the coldness of purely technical interfaces.

The system rejects the anti-references from PRODUCT.md: no cluttered dashboards with competing visual elements, no overly playful or whimsical treatments unsuitable for serious organizational work, and no dark mode forced as default.

**Key Characteristics:**
- Restrained color strategy: brand blue accent accounted for in moderation; grays and whites dominate
- Deep typographic hierarchy: headline, title, body, and label scales well-differentiated in weight and size
- Layered elevation: flat by default, with subtle shadows for depth only when interactive or elevated
- Mobile-first responsive grid that scales from 375px (mobile) to 2000px+ (ultra-wide)
- Accessible light mode as primary, dark mode as opt-in enhancement

## 2. Colors

The palette is restrained and professional: one saturated brand accent (brand blue) carries the primary action and focus states at ≤10% of any screen. Warm grays provide hierarchy and calm. Semantic colors (success, error, warning) carry status meaning without decoration.

### Primary

- **Brand Blue** (#465fff): The accent of action. Used for primary buttons, links, active states, focus rings, and brand highlights. Appears in moderation, creating visual rest and clarity.
- **Brand Blue Dark** (#2a31d8): The hover and active states of the primary brand blue. Used for button presses and emphasizing currently-active navigation items.

### Secondary

- **Blue Light** (#0ba5ec): A secondary accent for supporting actions and secondary information. Used sparingly; less saturated than brand blue but more readable at small sizes. Fallback when brand blue is unavailable.
- **Blue Light Dark** (#026aa2): Dark variant of blue light; used for hover states and active secondary buttons.

### Tertiary

- **Orange Base** (#fb6514): Accent for attention-seeking states (new items, highlights). Used only for special emphasis; limited to 5% of any screen.

### Neutral

- **Gray Light** (#f9fafb): Surfaces, backgrounds, hover states. The lightest neutral; used for secondary container backgrounds and light hover effects.
- **Gray Medium** (#e4e7ec): Borders, dividers, subtle separation. Used to create visual hierarchy through tonal layering rather than heavy borders.
- **Gray Text Primary** (#344054): Body text and primary labels. Default text color; highly legible on white backgrounds.
- **Gray Text Secondary** (#667085): Secondary text, metadata, helper text. Reduced prominence while maintaining legibility.
- **White** (#ffffff): Card backgrounds, input backgrounds, primary container surfaces.
- **Black** (#101828): Reserved for the darkest text when maximum contrast is needed (rare; Gray Text Primary is the standard).

### Semantic

- **Success** (#12b76a): Confirmation, completed states, positive actions. Applied to success badges, checkmarks, "approved" states.
- **Error** (#f04438): Destructive actions, errors, invalid input. Applied to delete buttons, error messages, validation failures.
- **Warning** (#f79009): Caution, pending states, informational alerts. Applied to warning messages and attention-needed states.

### Named Rules

**The One Accent Rule.** The brand blue (#465fff) accent is used on ≤10% of any given screen. Its scarcity is the point: it signals primary actions and draws the eye. Overuse diminishes its power and creates visual noise. Every secondary, tertiary, and interactive button must check: does this reduce brand blue's effectiveness?

**The Gray Hierarchy Rule.** Gray text follows a strict three-tier hierarchy: Gray Text Primary for body content and labels; Gray Text Secondary for helper text and metadata; and Gray Light for container backgrounds. Do not invent intermediate grays. The scale is intentional and limited.

## 3. Typography

**Display Font:** Outfit (sans-serif, variable weight 100–900)  
**Body Font:** Outfit (same family, no separate system font)  
**Mono (if needed):** Outfit does not have a true monospace variant; use Gray Text Primary (14px) for code snippets and maintain paragraph structure.

**Character:** Outfit is a humanist sans with subtle geometric edges. Its broad weight range (100–900) enables expressive hierarchy using weight alone; at large sizes, lighter weights feel elegant; at small sizes, medium/bold weights ensure legibility. The pairing of a single family avoids font-switching overhead while maintaining strong visual contrast through weight and scale.

### Hierarchy

- **Display** (500 weight, 72px / clamp(48px, 5vw, 72px), 1.25 line-height): Hero headlines and page titles only. Appears once per page, at the top. Never multiple displays on one screen.
- **Headline** (500 weight, 48px, 1.2 line-height): Section headers, major card titles, prominent data labels. Creates visual anchors within a section.
- **Title** (600 weight, 30px, 1.3 line-height): Subsection headers, card headers, emphasis text. Lighter than headline but more prominent than body.
- **Body** (400 weight, 14px, 1.5 line-height): Default text, paragraphs, form labels, table content. Max line length: 65–75 characters (enforced via `max-w-prose` or similar container widths). Slightly higher line-height than typical (1.5 vs 1.25) to maintain breathing room in data-dense interfaces.
- **Label** (600 weight, 12px, 1.5 line-height, 0.5px letter-spacing, uppercase): Form labels, table headers, badges, small category text. The uppercase treatment and tight letter-spacing differentiate it from body without introducing a separate typeface.

### Named Rules

**The Weight-Over-Size Rule.** Hierarchy is driven primarily through font weight (300, 400, 500, 600, 700) and secondarily through size scaling. A 500-weight Outfit at 72px feels just as distinct from 400-weight at 48px as a 700-weight would, with more elegance. Avoid creating new sizes; instead, vary weight within the established scale.

## 4. Elevation

This system uses **flat-by-default with layered shadows for specific states**. No drop shadows appear on elements at rest. Shadows emerge only to signal elevation (hover, focus, modal overlay) or to create subtle depth between cards and background.

### Shadow Vocabulary

- **Shadow Extra Small** (0px 1px 2px rgba(16, 24, 40, 0.05)): Rarely used; near-invisible lift for minimal hover feedback.
- **Shadow Small** (0px 1px 3px rgba(16, 24, 40, 0.1), 0px 1px 2px rgba(16, 24, 40, 0.06)): Default interaction shadow. Applied on button hover, input focus (subtle), and subtle elevation.
- **Shadow Medium** (0px 4px 8px -2px rgba(16, 24, 40, 0.1), 0px 2px 4px -2px rgba(16, 24, 40, 0.06)): Card elevation at rest (optional; many cards are flat). Applied to modals and dropdowns to create clear separation.
- **Shadow Large** (0px 12px 16px -4px rgba(16, 24, 40, 0.08), 0px 4px 6px -2px rgba(16, 24, 40, 0.03)): Modal overlays, floating panels, and significant elevation changes.
- **Focus Ring** (0px 0px 0px 4px rgba(70, 95, 255, 0.12)): Keyboard focus indicator. A soft blue glow using the brand color at low opacity; visible but not aggressive.

### Named Rules

**The Flat-By-Default Rule.** Surfaces are flat and rest on a neutral background. Shadows appear only as a response to state (hover, focus, elevation, modal). This keeps the interface calm and reduces visual noise, which is critical in data-dense admin dashboards.

## 5. Components

### Buttons

**Character:** Confident and direct. Primary buttons (brand blue) signal the main action on a screen. Secondary and tertiary buttons recede visually, allowing the primary to stand out.

- **Shape:** Rounded corners (8px). Modern, approachable, softer than sharp corners but more structured than fully rounded.
- **Primary Button:** Brand blue background (#465fff), white text, 10px vertical / 16px horizontal padding. Hover: darker blue (#2a31d8). Active: brand blue with brief press-down animation (no actual depth change; CSS transform only).
- **Secondary Button:** Gray light background (#f9fafb), gray text primary text color, same padding. Hover: slightly darker gray. Used for non-primary actions (cancel, back, secondary navigation).
- **Tertiary / Ghost:** Transparent background, brand blue text. Hover: gray light background tint. Used for inline actions, less prominent workflows.
- **Disabled State:** Reduced opacity (0.5) regardless of variant. Cursor not-allowed. No interaction feedback.
- **Icon Buttons:** 36–40px square, rounded 8px. Icons centered. Used for compact action toolbars (edit, delete, view).

### Cards / Containers

- **Corner Style:** Rounded corners (12px). Consistent with the button radius scale but slightly rounder, signaling "container" over "action."
- **Background:** White (#ffffff) by default. Light gray (#f9fafb) for optional alternating row or secondary grouping.
- **Border:** Optional 1px border in gray medium (#e4e7ec) to delineate from background. Many cards omit the border and rely on shadow or background tint.
- **Shadow Strategy:** Flat by default (no shadow). On hover or focus, apply Shadow Medium. Modals and floating panels use Shadow Large.
- **Internal Padding:** 16–20px. Consistent with spacing scale. Dense data tables may use tighter 12px padding.

### Inputs / Fields

- **Style:** White background, 1px border in gray medium (#e4e7ec). Rounded corners (8px). 8px vertical / 12px horizontal padding.
- **Focus:** Border shifts to brand blue (#465fff); background remains white. Optionally add Focus Ring shadow (the soft blue glow) for keyboard focus.
- **Error State:** Border shifts to error red (#f04438). Paired with error text (same color) below or inside the field.
- **Disabled State:** Background gray light (#f9fafb), text gray secondary (#667085), cursor not-allowed.
- **Placeholder:** Gray secondary text (#667085). Never use placeholder as a substitute for a label; always pair with a visible label above.

### Navigation

- **Sidebar (Primary Nav):** Collapsible or fixed sidebar with menu items stacked vertically. Active item: gray light background (#f9fafb) with brand blue text (#465fff) and icon. Hover (inactive): gray light background tint. Inactive text: gray text secondary (#667085).
- **Breadcrumb (Secondary Nav):** Horizontal path using body text (14px) with `/` or `>` separators. Last item bold. Used at top of content area to show location in hierarchy.
- **Tabs:** Horizontal group of buttons. Active tab: border-bottom brand blue (2px); text brand blue. Inactive tabs: gray text secondary; no border. Smooth transition on click.

### Modals

- **Backdrop:** Translucent dark overlay (rgba(16, 24, 40, 0.5) or similar). Blocks interaction with background.
- **Modal Box:** White background, rounded corners (12px), Shadow Large. Centered on screen or 1:1 aspect for wide screens. Responsive: full width on mobile with safe margin (8px–16px).
- **Header:** Title (Title weight/size), optional description. No heavy decoration; subtle border-bottom (gray medium) optional.
- **Body:** Primary content area. Padding 20px. Max-width ~600px for readability.
- **Footer:** Action buttons (primary + secondary, or cancel + confirm). Aligned right. Padding 16px top and 20px bottom.
- **Close Button:** Top-right corner, icon-only, 36px square. Keyboard: Escape key also closes.

### Tables

**Desktop View (≥768px):**
- Striped or flat rows with subtle gray alternation (row background: white, hover: gray light tint).
- Header row: gray light background (#f9fafb), gray text secondary text (#667085), uppercase 12px labels.
- Borders: optional horizontal dividers in gray medium (#e4e7ec). No vertical dividers.
- Hover effect: gray light background tint on row hover, brief Shadow Small on lift.

**Mobile View (<768px):**
- **Card Layout:** Each row becomes a card (rounded 12px, white background, subtle border or shadow). Stacked vertically with 8px gap.
- **Label-Value Pairs:** Each cell renders as `label: value` (flexbox row). Label in gray text secondary (bold), value in gray text primary.
- **Actions:** Swipe-to-reveal (if needed) or dedicated action button/dropdown within each card footer.
- **Compact Header:** Minimal; no column headers above cards. Rely on card labels instead.

**Responsive Breakpoints:**
- **Mobile (< 640px)**: Cards.
- **Tablet (640px – 1024px)**: Reduced-column table or card view; depends on column count and data density.
- **Desktop (≥1024px)**: Full table.

## 6. Do's and Don'ts

### Do:

- **Do** use brand blue (#465fff) for primary actions and link focus states only. Limit to ≤10% of any screen.
- **Do** use Gray Text Primary (#344054) for all body copy and default labels. Never use black (#101828) for body text.
- **Do** respect the typographic hierarchy: Display → Headline → Title → Body → Label. Do not create intermediate sizes.
- **Do** apply shadows only on state change: hover, focus, elevation (modals, floating panels). Flat at rest.
- **Do** use the full mobile-first grid (375px+ mobile, 768px tablet, 1024px+ desktop). Test at each breakpoint.
- **Do** transform tables into cards on mobile. Do not attempt to fit a table on a small screen; it becomes unreadable.
- **Do** hide or conditionally show action buttons based on user permissions, respecting PRODUCT.md's authorization boundaries. Never show disabled buttons as a workaround.
- **Do** use Outfit at multiple weights (400, 500, 600) to create hierarchy without introducing new typefaces.
- **Do** pair semantic colors (success, error, warning) with supporting text or icons; never rely on color alone for critical information.
- **Do** apply Focus Ring shadow (the soft blue glow) to interactive elements on keyboard focus for accessibility.

### Don't:

- **Don't** use dark mode as the default aesthetic. Light mode is primary; dark mode is opt-in enhancement.
- **Don't** create "cluttered dashboards with competing visual elements" (anti-reference from PRODUCT.md). Every element must earn its place.
- **Don't** use overly playful or whimsical treatments unsuitable for serious organizational work. This is an admin tool, not a game.
- **Don't** use brand blue text on gray backgrounds or gray backgrounds behind brand blue text; contrast degrades.
- **Don't** introduce new colors outside the defined palette. If you need a new semantic role, propose it and add it to the system.
- **Don't** use side-stripe borders (`border-left` or `border-right` > 1px) as colored accents on cards or list items. Use full borders, background tints, or leading icons instead.
- **Don't** use gradient text or glassmorphism effects. Decoration without purpose breaks the professional aesthetic.
- **Don't** stack more than 2 headings of different sizes (e.g., Display + Title) on one screen. Too much hierarchy feels chaotic.
- **Don't** use more than one sans-serif typeface. Outfit is the system font; no secondary display font.
- **Don't** bypass the permission system to show disabled buttons. If a user cannot perform an action, the button should not appear.
- **Don't** assume dark backgrounds render the same colors as light backgrounds. Test semantic colors (success, error, warning) in both modes; ensure legibility in both.
