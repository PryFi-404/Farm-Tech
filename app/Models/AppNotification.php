<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = ['user_id', 'title', 'message', 'type', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Which user this notification belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: mark as read
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    // Scope to get only unread notifications
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
