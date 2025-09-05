# User Dashboard Documentation

## Overview
The user dashboard provides a comprehensive interface for customers to manage their account, view orders, and track their shopping activity. The dashboard is designed to match the existing frontend theme and provides a modern, responsive user experience.

## Features

### 1. Dashboard Overview (`/dashboard`)
- **Statistics Cards**: Display total orders, completed orders, pending orders, and total amount spent
- **Recent Orders**: Shows the last 5 orders with quick actions
- **Profile Summary**: User information and member since date
- **Quick Actions**: Direct links to common tasks
- **Order Status Chart**: Visual breakdown of order statuses with percentages

### 2. Profile Management (`/profile`)
- **Personal Information**: Edit name, email, phone, and address details
- **Password Change**: Secure password update functionality
- **Account Information**: Display member since date, email verification status, and role

### 3. Order Management (`/orders`)
- **Order History**: Paginated list of all user orders
- **Status Filtering**: Filter orders by status (pending, processing, completed, etc.)
- **Order Details**: View individual order information
- **Tracking Information**: Display tracking numbers and shipping details
- **Print Functionality**: Generate invoices and shipping labels

### 4. Order Details (`/orders/{id}`)
- **Order Status**: Current status with visual indicators
- **Order Items**: Detailed list of purchased products with images
- **Addresses**: Shipping and billing address information
- **Order Summary**: Complete breakdown of costs including discounts
- **Order Timeline**: Visual representation of order progress
- **Tracking Modal**: Package tracking information

## Routes

### Protected Routes (Require Authentication)
```php
// Dashboard
GET /dashboard - Main dashboard page

// Profile Management
GET /profile - Profile edit page
PUT /profile - Update profile information
POST /profile/change-password - Change password

// Order Management
GET /orders - Order history page
GET /orders/{order} - Individual order details
```

## Database Changes

### Users Table
Added new profile fields:
- `phone` (string, nullable)
- `address` (text, nullable)
- `city` (string, nullable)
- `state` (string, nullable)
- `zip_code` (string, nullable)
- `country` (string, nullable)

### Models

#### User Model
- Added `orders()` relationship
- Added `role()` relationship
- Updated `$fillable` array with new profile fields

#### Order Model
- Added `getStatusColorAttribute()` method for badge colors

## UI/UX Features

### Design Consistency
- Matches existing frontend theme with Bootstrap 5
- Uses consistent color scheme and typography
- Responsive design for all screen sizes
- Smooth hover animations and transitions

### Interactive Elements
- Status filtering with JavaScript
- Modal dialogs for tracking information
- Toast notifications for success/error messages
- Hover effects on cards and buttons

### Accessibility
- Proper ARIA labels and semantic HTML
- Keyboard navigation support
- Color contrast compliance
- Screen reader friendly

## Security Features

### Authentication
- All dashboard routes protected by `auth` middleware
- Users can only view their own orders
- CSRF protection on all forms
- Secure password change functionality

### Data Validation
- Input validation on all forms
- SQL injection prevention
- XSS protection through proper escaping

## Usage Examples

### Accessing the Dashboard
1. User logs in to the application
2. Clicks on their name in the navigation dropdown
3. Selects "Dashboard" from the menu
4. Views their order statistics and recent activity

### Updating Profile
1. Navigate to Profile page
2. Update personal information
3. Submit form to save changes
4. Receive success confirmation

### Viewing Orders
1. Click "My Orders" from dashboard or navigation
2. Browse order history with pagination
3. Filter by status if needed
4. Click on individual orders for detailed view

### Tracking Packages
1. View order details
2. Click tracking button if available
3. Modal shows tracking number and shipping method
4. Use tracking number on carrier's website

## Future Enhancements

### Potential Additions
- Order cancellation functionality
- Return/refund requests
- Wishlist management
- Product reviews and ratings
- Email notifications for order updates
- Advanced order filtering and search
- Export order history to PDF/CSV
- Integration with shipping carriers for real-time tracking

### Technical Improvements
- Real-time order status updates via WebSockets
- Mobile app integration
- API endpoints for third-party integrations
- Advanced analytics and reporting
- Multi-language support
- Dark mode theme option

## Maintenance

### Regular Tasks
- Monitor order status accuracy
- Update tracking information
- Review and optimize database queries
- Test responsive design on new devices
- Update security measures as needed

### Performance Optimization
- Implement caching for frequently accessed data
- Optimize database queries for large order histories
- Use lazy loading for images
- Implement pagination for better performance 