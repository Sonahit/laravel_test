<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Builder::macro('whereLike', function (array $attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach ($attributes as $attribute) {
                    $query->where(DB::raw($attribute), 'LIKE', "%{$searchTerm}%");
                }
            });
            return $this;
        });
        Builder::macro('orWhereLike', function (array $attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach ($attributes as $attribute) {
                    $query->orWhere(DB::raw($attribute), 'LIKE', "%{$searchTerm}%");
                }
            });
            return $this;
        });
        Builder::macro('havingLike', function (array $attributes, string $searchParam) {
            if(!intval($searchParam)) return $this;
            $val = intval($searchParam);
            foreach ($attributes as $attribute) {
              $this->having(DB::raw($attribute), "LIKE", "%{$val}%");
            };
            return $this;
        });
        Builder::macro('orHavingLike', function (array $attributes, string $searchParam) {
            if(!intval($searchParam)) return $this;
            $val = intval($searchParam);
            foreach ($attributes as $attribute) {
              $this->orHaving(DB::raw($attribute), "LIKE", "%{$val}%");
            };
            return $this;
        });
        
    }
}
