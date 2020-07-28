<?php

namespace App\Providers;

use App\Address;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            // $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            // $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
        }

        // only owner access to model
        Gate::define('owner-model', function (User $user, Model $model) {
            $modelUserId = (int) $model->user_id ?? 0;
            return $modelUserId === $user->id;
        });

        // update or delete any model
        Gate::define('update-model', function (User $user, Model $model) {
            return $user->isAdmin()
                || $user->isSuper()
                || $user->id === (int) $model->user_id;
        });

        // admin or super or user owner profile
        Gate::define('visit-user-profile', function (User $user, ?string $userId) {

            // dd($userId);
            if (is_null($userId)) {
                // userProfileController will return current loggend in user profile
                return true;
            }

            return $user->isAdmin()
                || $user->isSuper()
                || $user->enc_id === $userId;
        });

        // admin super only
        Gate::define('root', function (User $user) {
            return $user->isAdmin() || $user->isSuper();
        });

        // delivery
        Gate::define('delivery', function (User $user) {
            return $user->isAdmin() ||
                $user->isSuper() ||
                $user->isDelivery();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('js', function ($arr) {
            [$key, $val] = explode(',', $arr);

            $command = "";
            $command .= "<?php \$__env->startPush('scripts'); ?>\n";
            $command .= "<script>window['xjs'] = window['xjs'] || {};xjs['$key'] = '<?=trim($val)?>';</script>\n";
            $command .= "<?php \$__env->stopPush(); ?>\n";

            return $command;
        });

        /**
         * Paginate a standard Laravel Collection.
         *
         * @param int $perPage
         * @param int $total
         * @param int $page
         * @param string $pageName
         * @return array
         */
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        // Route::model('address', Address::class);

        Blade::if('url', function (string $uri) {
            return Request::is($uri);
        });
    }
}
