<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'contact_category_id',
        'message',
        'status',
        'admin_reply',
        'replied_at',
    ];
    
    // Status constants
    const STATUS_NEW = 'new';
    const STATUS_AWAITING_RESPONSE = 'awaiting_response';
    const STATUS_COMPLETED = 'completed';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function getStatuses(): array
    {
        return [
            self::STATUS_NEW => 'New Enquiry',
            self::STATUS_AWAITING_RESPONSE => 'Awaiting Response',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    public static function getStatusColor(string $status): string
    {
        return match($status) {
            self::STATUS_NEW => 'danger',
            self::STATUS_AWAITING_RESPONSE => 'warning',
            self::STATUS_COMPLETED => 'success',
            default => 'secondary',
        };
    }

    public function category()
    {
        return $this->belongsTo(ContactCategory::class, 'contact_category_id');
    }
}
