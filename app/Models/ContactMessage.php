<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['name', 'email', 'subject', 'message', 'status', 'reply', 'replied_at'];

    protected $casts = ['replied_at' => 'datetime'];

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function markAsRead(): void
    {
        $this->update(['status' => 'read']);
    }
}
