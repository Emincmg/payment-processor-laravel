<?php

namespace Emincmg\PaymentProcessorLaravel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

abstract class Payment extends Model
{
    protected $table = 'payments';
    public $timestamps = true;

    protected $fillable = [
        'channel',
        'amount',
        'currency',
        'status',
        'confirmed_at',
        'declined_at',
        'tries',
    ];

    protected $attributes = [
        'tries' => 0,
        'status' => 'pending',
    ];

    protected $casts = [
        'channel' => 'string',
        'amount' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'declined_at' => 'datetime',
    ];

    /**
     * Mutator: `amount` automatically clean the amount field.
     */
    public function setAmountAttribute($value): void
    {
        $this->attributes['amount'] = number_format((float) $value, 2, '.', '');
    }

    /**
     * Mutator: `channel` clean channel attribute against XSS.
     */
    public function setChannelAttribute($value): void
    {
        $this->attributes['channel'] = strip_tags(trim($value));
    }

    /**
     * Mutator: `status` limit the variable to certain values only.
     */
    public function setStatusAttribute($value): void
    {
        $allowedStatuses = ['pending', 'confirmed', 'declined', 'failed'];
        $this->attributes['status'] = in_array($value, $allowedStatuses) ? $value : 'pending';
    }

    /**
     * Accessor: `amount` encrypt the variable when storing, decrypt when retrieving.
     */
    public function getAmountAttribute($value): float
    {
        return (float) Crypt::decryptString($value);
    }

    public function setAmountEncryptedAttribute($value): void
    {
        $this->attributes['amount'] = Crypt::encryptString($value);
    }

    /**
     * Access Control: Only authorized account can access the model.
     */
    public function isEditableByUser($user): bool
    {
        return $user->hasRole('admin') || $this->user_id === $user->id;
    }
}
