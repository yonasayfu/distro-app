<?php

namespace App\Models;

use Database\Factories\ImportRunFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportRun extends Model
{
    /** @use HasFactory<ImportRunFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'resource',
        'status',
        'file_name',
        'rows_count',
        'valid_rows_count',
        'imported_rows_count',
        'summary',
        'preview_rows',
        'completed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'summary' => 'array',
            'preview_rows' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
