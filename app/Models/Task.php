<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    public $table = 'tasks';

    protected $dates = [
        'due_date',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'created_by',
        'assignee_id',
        'related_task_id',
        'created_at',
        'updated_at',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'related_task_id');
    }

    public function relatedTasks(): HasMany
    {
        return $this->hasMany(self::class, 'related_task_id');
    }
}
