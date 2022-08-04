<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'field_param_id',
        'field_type_id',
        'is_public',
    ];


    /** Related to Custom Field Param */
    public function customParam()
    {
        return $this->belongsTo(CustomFieldParams::class, 'field_param_id', 'id');
    }

    /** Related to Custom Field Type */
    public function customType()
    {
        return $this->belongsTo(CustomFieldType::class, 'field_type_id', 'id');
    }

}
