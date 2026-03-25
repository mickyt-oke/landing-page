<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Application extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * @var list<string>
     */
    protected $fillable = [
'user_id',
        'application_reference',
        'ack_ref_number',
        'submitted_at',
        'full_name',
        'passport_number',
        'nationality',
        'visa_category',
        'arrival_date',
        'overstay_days',
        'status',
        'applicant_note',
        'reviewer_comment',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
'arrival_date' => 'date',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'overstay_days' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }
}
