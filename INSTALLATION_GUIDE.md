# Lost & Found System - Installation Guide

## Features Implemented

✅ **Feature 1: Admin Verifikasi Laporan**
- Admin can approve/reject lost and found reports
- Notifications sent to users when reports are verified
- Pending verification dashboard for admins

✅ **Feature 2: User Klaim Barang (Claim System)**
- Users can claim found items
- Upload proof of ownership
- Admin approval workflow
- Notifications for all parties involved

✅ **Feature 3: Admin Catatan (Admin Notes)**
- Admins can add internal notes to any report
- Notes are only visible to admins
- Useful for tracking investigation progress

✅ **Feature 4: Serah Terima Barang (Handover System)**
- Both user and admin upload handover proof photos
- Status tracking: pending → user_uploaded → completed
- Automatic notifications when completed

✅ **Feature 5: Sistem Notifikasi (Notification System)**
- Database-based notifications
- Notification bell in navbar with unread count
- Types: success, danger, warning, info
- Mark as read functionality

✅ **Feature 6: Rating & Feedback**
- Users can rate the system after completing handover
- 1-5 star rating system
- Optional comments
- Admin can view all feedback

✅ **Feature 7: Chat "Butuh Bantuan?" (User-Admin Chat)**
- Real-time messaging between users and admins
- Auto-refresh every 3 seconds
- Read status tracking
- Simple and clean interface

✅ **Feature 8: Admin Dashboard Statistik (Statistics & Charts)**
- Verification status charts (Doughnut charts)
- Claims status visualization
- Monthly trend analysis (Line chart)
- Items by category (Bar chart)
- Average rating per category
- Average completion time
- All powered by Chart.js

---

## Installation Steps

### 1. Run Migrations

```bash
php artisan migrate
```

This will create all new tables:
- `notifications` - Notification system
- `claims` - Claim management
- `notes` - Admin notes
- `serah_terima` - Handover tracking
- `feedback` - Rating & feedback
- `messages` - Chat system

It will also add new fields to existing tables:
- `lost_items` & `found_items` get: `status_verifikasi`, `alasan_reject`, `verified_at`, `verified_by`

### 2. Create Storage Directories

```bash
php artisan storage:link

# Create necessary image directories
mkdir -p storage/app/public/images/claims
mkdir -p storage/app/public/images/serah-terima
```

### 3. Verify Routes

All routes are automatically added. Test with:

```bash
php artisan route:list
```

Key new routes:
- `/admin/verifikasi` - Admin verification page
- `/admin/claims` - Claims management
- `/admin/statistics` - Statistics dashboard
- `/claims/create/{foundItem}` - Claim form
- `/notifications` - User notifications
- `/messages` - User-Admin chat
- `/serah-terima/{claim}` - Handover page
- `/feedback/create/{claim}` - Feedback form

### 4. Seed Test Data (Optional)

To test the system, you can manually:
1. Create some lost/found items as a regular user
2. They will be in "pending" status
3. Login as admin to verify them
4. Regular users can then claim items
5. Admin approves claims
6. Both parties complete handover
7. User provides feedback

---

## New Models Created

1. **Notification** - `app/Models/Notification.php`
2. **Claim** - `app/Models/Claim.php`
3. **Note** - `app/Models/Note.php`
4. **SerahTerima** - `app/Models/SerahTerima.php`
5. **Feedback** - `app/Models/Feedback.php`
6. **Message** - `app/Models/Message.php`

All models have proper relationships and helper methods.

---

## New Controllers Created

1. **VerificationController** - Handles report verification
2. **ClaimController** - Manages claims
3. **NoteController** - Admin notes
4. **SerahTerimaController** - Handover process
5. **NotificationController** - Notifications
6. **FeedbackController** - Rating & feedback
7. **MessageController** - Chat system

Updated controllers:
- **DashboardController** - Added statistics methods
- **LostItemController** - Added verification filter
- **FoundItemController** - Added verification filter

---

## New Views Created

### Admin Views:
- `resources/views/admin/verifikasi/index.blade.php` - Verification dashboard
- `resources/views/admin/claims/index.blade.php` - Claims list
- `resources/views/admin/claims/show.blade.php` - Claim detail
- `resources/views/admin/serah-terima/show.blade.php` - Admin handover view
- `resources/views/admin/feedback/index.blade.php` - Feedback list
- `resources/views/admin/messages/index.blade.php` - Chat users list
- `resources/views/admin/messages/chat.blade.php` - Chat with user
- `resources/views/dashboard/statistics.blade.php` - Statistics dashboard

### User Views:
- `resources/views/claims/create.blade.php` - Claim form
- `resources/views/claims/my-claims.blade.php` - User's claims
- `resources/views/serah-terima/show.blade.php` - User handover view
- `resources/views/feedback/create.blade.php` - Feedback form
- `resources/views/notifications/index.blade.php` - Notifications
- `resources/views/messages/user.blade.php` - User chat with admin

Updated views:
- `resources/views/layouts/app.blade.php` - Added notification bell, chat link, new menu items
- `resources/views/found-items/show.blade.php` - Added "Claim" button

---

## Key Features & Flow

### 1. Report Verification Flow
```
User creates report → Status: pending
↓
Admin reviews in /admin/verifikasi
↓
Admin approves/rejects
↓
User receives notification
↓
If approved: Visible to all users
If rejected: Only visible to owner
```

### 2. Claim Flow
```
User sees approved found item
↓
Clicks "Klaim Barang"
↓
Fills form + uploads proof (optional)
↓
Admin reviews in /admin/claims
↓
Admin accepts/rejects
↓
If accepted: Serah terima unlocked
```

### 3. Handover Flow
```
Claim accepted
↓
User uploads handover photo
↓
Admin uploads handover photo
↓
Status: completed
↓
User can give feedback
```

### 4. Notification Triggers
- Report approved/rejected
- Claim submitted
- Claim accepted/rejected
- Handover completed
- Admin messages received

---

## UI Features

### Navbar Additions:
- **Notification Bell** - Shows unread count
- **Chat Icon** - Quick access to help chat
- **Admin Menu**: Verifikasi, Klaim, Statistik
- **User Menu**: Klaim Saya

### Dashboard:
- **Admin**: Statistics with charts
- **User**: Overview of reports

---

## Database Schema

### New Tables:

```sql
notifications (
    id, user_id, title, message, link, type, read_status, timestamps
)

claims (
    id, user_id, found_item_id, deskripsi_klaim, bukti, status, 
    alasan_reject, reviewed_by, reviewed_at, timestamps
)

notes (
    id, admin_id, report_type, report_id, isi_catatan, timestamps
)

serah_terima (
    id, claim_id, bukti_serah_terima_user, bukti_serah_terima_admin,
    user_uploaded_at, admin_uploaded_at, completed_at, status, timestamps
)

feedback (
    id, claim_id, user_id, rating, komentar, timestamps
)

messages (
    id, sender_id, receiver_id, message, read_status, timestamps
)
```

### Modified Tables:
- `lost_items` + `found_items`: Added verification fields

---

## Testing the System

1. **As User:**
   - Create a lost/found report
   - View it in your list (status: pending)
   - Wait for admin approval
   - After approval, claim someone else's found item
   - Complete handover
   - Give feedback

2. **As Admin:**
   - Go to /admin/verifikasi
   - Approve/reject pending reports
   - Go to /admin/claims
   - Review and approve claims
   - Go to /admin/serah-terima
   - Upload handover photo
   - View statistics at /admin/statistics

---

## Troubleshooting

### Images not showing:
```bash
php artisan storage:link
```

### Notifications not appearing:
Check if user_id exists in notifications table and read_status is false.

### Charts not loading:
Ensure Chart.js CDN is accessible:
```html
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
```

### Migration errors:
If you get foreign key errors, ensure migrations run in order. Check the date prefix on migration files.

---

## Security Notes

- All routes are protected by `auth` middleware
- Admin routes require `admin` middleware
- Users can only claim items they don't own
- Verification ensures quality control
- File uploads are validated (image types, max size)

---

## Performance Tips

1. **Enable Caching:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **Database Indexing:**
All foreign keys and frequently queried fields are indexed.

3. **Image Optimization:**
Consider using Laravel's image intervention package for automatic resizing.

---

## Next Steps (Optional Enhancements)

- Email notifications
- Real-time WebSocket chat (instead of polling)
- Export reports to PDF
- Advanced search with Elasticsearch
- Mobile app API
- Push notifications

---

## Support

All features are fully integrated and tested. The UI matches your provided screenshots.

**Important:** Make sure to run migrations before testing!

```bash
php artisan migrate
```

Happy coding! 🚀

