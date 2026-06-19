# Product

## Register

product

## Users

**Admin**: Superusers who manage the entire organizational structure, all dioceses, nucleos, secretarias, and dirigentes across the system.

**Diocese Users**: Managers who oversee their own diocese and all child entities (nucleos and secretarias within their diocese). They can create, edit, and delete child entities but cannot modify other dioceses.

**Nucleo/Secretaria Users**: Managers who oversee only their own nucleo or secretaria. They can view and edit their own entity's details but have limited visibility to the broader organizational structure.

**Context**: The app runs on both desktop and mobile. Users at all levels need to manage entities on-the-go, check quick information, and make administrative decisions from various devices.

## Product Purpose

TLC Admin is an organizational management dashboard for ecclesiastical institutions. It enables administrators, diocese leaders, and nucleo/secretaria managers to:

- View, create, edit, and delete dioceses, nucleos (regional groups), secretarias (departments), and dirigentes (leaders)
- Manage hierarchical relationships between entities and their sub-entities
- Control access and permissions based on organizational scope
- Access key organizational information (coordinators, upcoming events, leader skills) quickly

Success looks like: users trust the permission system, never see disabled actions, and find critical information without deep navigation.

## Brand Personality

Professional, efficient, and trustworthy. Designed in the TailAdmin style: clean, modern, corporate aesthetic. The interface should feel like a serious tool for serious organizational work—no unnecessary decoration, but not cold or intimidating. Competent and ready to handle complex hierarchical data.

**Three words**: Organized, Accessible, Trustworthy

## Anti-references

- Cluttered or overwhelming dashboards with competing visual elements
- Overly playful or whimsical interfaces for administrative work
- Dark mode forced as the default aesthetic (light mode is preferred)

## Design Principles

1. **Respect Permission Boundaries**: Never show actions the user cannot perform. Buttons and menu items must adapt to the user's scope and role. This builds trust.

2. **Clarity Through Context**: Present critical information (coordinators, upcoming events, skills) inline or via quick-access modals, not buried in deep navigation. Users should get answers in 1–2 interactions.

3. **Mobile-First Responsiveness**: All workflows must work on mobile, tablet, and desktop. Tabular data transforms into cards on small screens. No desktop-only features.

4. **Hierarchical Clarity**: Visually distinguish between entities a user can manage, entities they can view but not edit, and entities outside their scope. Use visual hierarchy (indentation, badges, disabled states) to make scope clear.

5. **Minimal Cognitive Load**: Every page should have a clear primary action (create, edit) and related secondary actions (view, delete). Avoid modals for simple actions; use modals for context (viewing details) or irreversible decisions (confirming deletion).

## Accessibility & Inclusion

WCAG 2.1 Level A compliance as a baseline, with best practices in keyboard navigation, color contrast, and semantic HTML. No formal AAA requirement, but strive for high usability for users of all abilities.
