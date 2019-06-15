<?php

namespace App\Providers;

use App\Http\ViewComposers\LazyViewComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        View::composer('components.lazy', LazyViewComposer::class);
    }
}
