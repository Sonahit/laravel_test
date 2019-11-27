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
                    $query->orWhere(DB::raw($attribute), 'LIKE', "%{$searchTerm}%");
                }
            });
            return $this;
        });
        Builder::macro('havingLike', function (array $attributes, string $searchParam) {
            if(!intval($searchParam)) return $this;
            foreach ($attributes as $attribute) {
              $this->orHaving(DB::raw($attribute), "LIKE", "%{$searchParam}%");
            };
            return $this;
        });
    }
}
