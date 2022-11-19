<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

    protected $table = 'coupons';
    protected $fillable = ['coupon', 'discount', 'user_id'];
    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    use HasFactory;


    /**
     * @return carbon
     */
    public function getStopDiscountAttribute():carbon {
        return Carbon::Parse($this->created_at)->addHours(3);
    }

}
