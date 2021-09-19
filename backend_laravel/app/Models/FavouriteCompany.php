<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteCompany extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_companies';

    /**
     * Scope a query to only include favorite based on company id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCompanyIdSearch($query, $search)
    {
        $query->where('company_id', '=', $search);
    }

    /**
     * Scope a query to only include favorite based on user id.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUserIdSearch($query, $search)
    {
        $query->where('user_id', '=', $search);
    }
}
