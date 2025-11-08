<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

     protected $table = 'transactions';

    protected $fillable = [
        'cashbook_id',
        'category_id',
        'payment_method_id',
        'party_name',
        'remark',
        'document',
        'amount',
        'transaction_datetime',
        'description',
        'custom_fields',
        'type',
        'status',
        'created_by',
        'updated_by',
    ];

    public $timestamps = true;

    protected $casts = [
        'custom_fields' => 'array',
        'transaction_datetime' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Relations
    public function cashbook() {
        return $this->belongsTo(Cashbook::class);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
