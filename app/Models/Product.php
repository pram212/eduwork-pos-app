<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class)->withTrashed();
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class)->withPivot('quantity')->withTrashed();
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)->withPivot('quantity')->withTrashed();
    }

}
