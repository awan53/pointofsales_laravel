<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'table_sales';

    protected $fillable = [
        'invoice',
        'sales_date',
        'total',
        'paid',
        'change',
    ];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        if (!$model->sales_date) {
            $model->sales_date = now(); // Mengisi dengan waktu sekarang otomatis
        }
    });
}

    public function items()
{
    return $this->hasMany(SaleItem::class, 'sales_id');
}
}

