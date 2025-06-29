<?php

namespace App\Models;

use App\Traits\crudBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory, SoftDeletes, crudBy;

    public function item_specification(): BelongsTo
    {
        return $this->belongsTo(ItemSpecification::class, 'item_specification_id');
    }

    public function item_category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function item_type(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    public function item_brand(): BelongsTo
    {
        return $this->belongsTo(ItemBrand::class, 'item_brand_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function item_stock(): HasOne
    {
        return $this->hasOne(ItemStock::class, 'item_id');
    }

    public static function understock_items()
    {
        return Item::join('item_stocks', 'items.id', '=', 'item_stocks.item_id')
            ->select('items.*', 'item_stocks.qty')
            ->whereRaw('item_stocks.qty < items.minimum_stock');
    }
}
