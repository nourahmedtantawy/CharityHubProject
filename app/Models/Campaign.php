<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'description', 'content', 'goal_amount',
        'raised_amount', 'currency', 'deadline', 'status',
        'featured_image', 'category', 'meta_title', 'meta_description', 'created_by',
    ];

    protected $casts = [
        'deadline'      => 'date',
        'goal_amount'   => 'decimal:2',
        'raised_amount' => 'decimal:2',
    ];

    // ── Auto-generate slug on create ──────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Campaign $campaign) {
            if (empty($campaign->slug)) {
                $campaign->slug = static::generateUniqueSlug($campaign->title);
            }
        });

        static::updating(function (Campaign $campaign) {
            if ($campaign->isDirty('title') && empty($campaign->slug)) {
                $campaign->slug = static::generateUniqueSlug($campaign->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    // ── Accessors ─────────────────────────────────────────────
    public function getProgressPercentageAttribute(): float
    {
        if ($this->goal_amount <= 0) return 0;
        return min(100, round(($this->raised_amount / $this->goal_amount) * 100, 1));
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->goal_amount - $this->raised_amount);
    }

    public function getDaysRemainingAttribute(): int
    {
        return max(0, now()->diffInDays($this->deadline, false));
    }

    public function getIsExpiredAttribute(): bool
    {
        return now()->isAfter($this->deadline);
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopeActive($query)    { return $query->where('status', 'active'); }
    public function scopePublished($query) { return $query->where('status', 'active')->where('deadline', '>=', now()); }

    // ── Relationships ─────────────────────────────────────────
    public function donations()     { return $this->hasMany(Donation::class); }
    public function shifts()        { return $this->hasMany(VolunteerShift::class); }
    public function impactReports() { return $this->hasMany(ImpactReport::class); }
    public function creator()       { return $this->belongsTo(User::class, 'created_by'); }
}