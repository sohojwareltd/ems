# Enquiry System Update Documentation

## Overview
The contact system has been completely redesigned into a comprehensive **Enquiry Management System** with dynamic categories, status tracking, and email reply functionality.

## Key Changes

### 1. **Renamed "Contacts" to "Enquiries"**
- Navigation label in admin panel changed from "Contacts" to "Enquiries"
- All references updated throughout the system
- Success message updated: "Your enquiry has been submitted successfully!"

### 2. **Dynamic Categories**
- **New Model**: `ContactCategory`
- **New Table**: `contact_categories`
- Categories are now manageable from the admin panel under **Settings → Enquiry Categories**
- Default categories seeded:
  - General Inquiry
  - Tuition
  - Technical Support
  - Billing
  - Other

#### Category Fields:
- `name` - Category name (e.g., "General Inquiry")
- `slug` - URL-friendly identifier (auto-generated)
- `description` - Optional description
- `is_active` - Enable/disable categories
- `sort_order` - Control display order

### 3. **Status Tracking**
Enquiries now have three status levels with color-coded badges:

| Status | Label | Badge Color | Purpose |
|--------|-------|-------------|---------|
| `new` | New Enquiry | Red (danger) | Just submitted, awaiting review |
| `awaiting_response` | Awaiting Response | Yellow (warning) | Under review, pending reply |
| `completed` | Completed | Green (success) | Reply sent, resolved |

### 4. **Reply Functionality**
- **Reply Action** available in the admin table view
- Opens a modal to compose reply message
- Sends email to customer with:
  - Admin's reply message
  - Original enquiry details quoted
  - Professional HTML template
- Automatically updates status to "Completed"
- Records reply time and message

## Database Changes

### New Tables
```sql
-- contact_categories table
CREATE TABLE contact_categories (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INTEGER DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Updated Tables
```sql
-- Added to contacts table
ALTER TABLE contacts ADD COLUMN:
- status ENUM('new', 'awaiting_response', 'completed') DEFAULT 'new'
- contact_category_id BIGINT NULL (foreign key to contact_categories)
- admin_reply TEXT NULL
- replied_at TIMESTAMP NULL
```

## File Changes

### Models
1. **`app/Models/Contact.php`**
   - Added status constants and color mapping
   - Added `category()` relationship
   - Added `getStatuses()` and `getStatusColor()` methods

2. **`app/Models/ContactCategory.php`** (NEW)
   - Auto-generates slug from name
   - `active()` scope for active categories
   - `ordered()` scope for sort order
   - `contacts()` relationship

### Resources (Admin Panel)
1. **`app/Filament/Resources/ContactResource.php`**
   - Updated navigation: icon, label, model label
   - Enhanced table columns: status badge, category, received date
   - Added status and category filters
   - Default sort by newest first
   - Reply action with email functionality
   - View, Edit, Delete actions

2. **`app/Filament/Resources/ContactCategoryResource.php`** (NEW)
   - Full CRUD for categories
   - Shows enquiry count per category
   - Active/inactive filtering
   - Auto-slug generation

### Controllers
**`app/Http/Controllers/PageController.php`**
- `contact()` method: loads active categories
- `store()` method: validates category_id, sets default status
- Updated success message

### Views
1. **`resources/views/frontend/partials/contact-form.blade.php`**
   - Changed "Subject" field to "Category" dropdown
   - Dynamically populated from database
   - Field name: `contact_category_id`

2. **`resources/views/emails/enquiry-reply.blade.php`** (NEW)
   - Professional HTML email template
   - Displays admin reply prominently
   - Quotes original enquiry with details
   - Brand colors (green #00b22d)

### Mailable
**`app/Mail/EnquiryReplyMail.php`** (NEW)
- Accepts `$enquiry` (Contact model) and `$replyMessage`
- Subject line includes category name
- Uses `emails.enquiry-reply` view

## Admin Panel Usage

### Managing Enquiries
1. Navigate to **Enquiries** in the sidebar
2. View all enquiries with status badges
3. Filter by status (New/Awaiting/Completed)
4. Filter by category
5. Default sort: newest first

### Replying to Enquiries
1. Click **Reply** button (green) on any non-completed enquiry
2. Compose your reply in the modal
3. Click **Send** - email sent automatically
4. Status changes to "Completed"
5. Reply and timestamp recorded

### Managing Categories
1. Navigate to **Settings → Enquiry Categories**
2. Create new categories with name and optional description
3. Set sort order (lower numbers appear first)
4. Toggle active/inactive
5. View enquiry count per category
6. Edit or delete categories

## Frontend Experience

### Contact Form
- **Category Dropdown**: Dynamically loaded from active categories only
- Shows categories in sort order
- Required field
- Old input preserved on validation errors

### Success Message
"Your enquiry has been submitted successfully! We will respond shortly."

## Email Template Features
- **Responsive Design**: Mobile-friendly HTML
- **Brand Colors**: Green accent (#00b22d)
- **Clear Structure**:
  1. Header with title
  2. Admin reply (highlighted with green border)
  3. Original enquiry quoted (gray border)
  4. Signature and footer

## Database Seeder

Run to populate default categories:
```bash
php artisan db:seed --class=ContactCategorySeeder
```

## Migrations

Two new migrations:
1. `2025_11_16_123004_create_contact_categories_table.php`
2. `2025_11_16_123225_add_status_and_category_to_contacts_table.php`

Already run successfully.

## Model Relationships

```php
// Contact model
$contact->category(); // belongsTo ContactCategory

// ContactCategory model
$category->contacts(); // hasMany Contact
```

## Admin Panel Permissions

All standard Filament permissions apply:
- `view_any_contact`
- `view_contact`
- `create_contact`
- `update_contact`
- `delete_contact`
- `view_any_contact_category`
- etc.

## Future Enhancements (Optional)

1. **Notification System**: Alert admins on new enquiries
2. **Assignment**: Assign enquiries to specific admin users
3. **Priority Levels**: Urgent/Normal/Low
4. **Internal Notes**: Add private notes without emailing customer
5. **Templates**: Pre-saved reply templates
6. **Auto-responses**: Automatic acknowledgment on submission
7. **SLA Tracking**: Track response times

## Testing Checklist

- [x] Categories seed correctly
- [x] Frontend form shows categories
- [x] New enquiries have "new" status
- [x] Admin can view all enquiries
- [x] Status badge colors display correctly
- [x] Reply action sends email
- [x] Status updates to completed after reply
- [x] Category filtering works
- [x] Status filtering works
- [x] View action displays full details
- [x] Edit action updates enquiry
- [x] Email template renders correctly

## Troubleshooting

### Categories not showing in form
- Check `is_active = true` in database
- Verify `contact()` method passes `$contactCategories` to view

### Reply email not sending
- Check mail configuration in `.env`
- Verify queue worker running if using queue
- Check `storage/logs/laravel.log` for errors

### Old contacts showing no category
- Normal - category was optional
- Filter works correctly with NULL values
- Display shows "—" for empty categories

## Support

For issues or questions about the enquiry system, check:
1. This documentation
2. Laravel logs: `storage/logs/laravel.log`
3. Filament documentation: https://filamentphp.com/docs

---

**Last Updated**: 16 November 2025  
**Version**: 2.0  
**Status**: Production Ready
