<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'completed'
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed' => 'boolean'
    ];

    public function isOverdue(): bool
    {
        return $this->due_date &&
            !$this->completed &&
            $this->due_date->isPast();
    }

    public function scopePending($query)
    {
        return $query->where('completed', false);
    }

    public function scopeCompleted($query)
    {
        return $query->where('completed', true);
    }

    public function scopeOrderByDueDate($query)
    {
        return $query->orderByRaw('due_date IS NULL, due_date ASC');
    }
}
