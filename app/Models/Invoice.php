<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'tenant_id',
        'invoice_number',
        'subtotal',
        'tax_amount',
        'total',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'status' => InvoiceStatus::class,
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getRemainingBalanceAttribute()
    {
        return $this->total - $this->payments()->sum('amount');
    }
}
