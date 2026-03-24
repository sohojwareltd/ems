<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmailGroup extends Model
{
    protected $fillable = [
        'title',
        'email',
        'parent_id',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(EmailGroup::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(EmailGroup::class, 'parent_id');
    }

    public function scopeGroups($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeEmails($query)
    {
        return $query->whereNotNull('parent_id');
    }

    public function isGroup(): bool
    {
        return is_null($this->parent_id);
    }

    public function isEmailEntry(): bool
    {
        return ! is_null($this->parent_id);
    }
}
