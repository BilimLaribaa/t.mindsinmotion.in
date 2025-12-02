

# **Minds in Motion – Internal Tools Suite**

**Domain:** `t.mindsinmotion.in`

This application provides a centralized suite of internal utilities used across Minds in Motion Foundation projects, events, workshops, and administrative workflows. The tools are designed for reliability, simplicity, and high-volume use during Education Expo activities and year-round operations.

---

## **Available Tools**

### **1. ID Card Generation Tool**

A browser-based system for creating bulk ID cards for students, faculty, volunteers, and event participants.
**Features:**

* Excel import
* PNG/JPEG template upload
* Drag-to-position text and photo fields
* Google Fonts support
* Batch export to ZIP (PNG format)
* All processing done locally in the browser

### **2. Certificate Generation Tool**

Generates certificates for events, competitions, and workshops.
**Features:**

* Upload certificate templates (PNG/PDF)
* Dynamic text fields (name, event, date, signatures)
* Excel-based bulk generation
* Optional QR code insertion
* High-resolution PNG/PDF output

### **4. Link Shortener**

Internal link-shortening system for events, forms, and documents.
**Features:**

* Branded short links (`t.mindsinmotion.in/l/...`)
* Basic click analytics
* Editable and removable links
* Optional password protection

### **3. QR Code Tool**

Creates static or text-based QR codes for internal use.
**Features:**

* URL/text input
* Custom size and margin settings
* Dynamic Links
* PNG/SVG download options

### **5. Image / File Upload Tool**

A utility for uploading and sharing internal files.
**Features:**

* Upload images, PDFs, and ZIP files
* Auto-generated share links
* Private/public toggle
* Optional expiry dates
* Lightweight storage dashboard

---

## **Architecture Overview**

- Frontend: Browser-based interface (framework-agnostic)
- Backend: Laravel (PHP)
- Database: SQLite

## **Requirements**

- Most file management will be done on the client browser
- 
