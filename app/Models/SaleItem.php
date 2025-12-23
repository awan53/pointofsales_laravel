<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;
    protected $table = 'table_sales_items';

    protected $fillable = [
        'sales_id',
        'product_id',
        'qty',
        'price',
        'subtotal'

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function sale()
{
    return $this->belongsTo(Sale::class, 'sales_id');
}

}
