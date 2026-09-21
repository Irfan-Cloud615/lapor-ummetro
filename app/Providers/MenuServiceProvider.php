<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
     $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
      $verticalMenuData = json_decode($verticalMenuJson);

      $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
      $horizontalMenuData = json_decode($horizontalMenuJson);

      // Filter menu berdasarkan role user yang login
      view()->composer('*', function ($view) use ($verticalMenuData, $horizontalMenuData) {
          $user = auth()->user();
          $role = $user ? $user->role : 'guest';

          // Filter vertical menu
          $filteredVertical = clone $verticalMenuData;
          $filteredVertical->menu = array_values(array_filter(
              $verticalMenuData->menu,
              fn($item) => !isset($item->roles) || in_array($role, $item->roles)
          ));

          // Filter horizontal menu
          $filteredHorizontal = clone $horizontalMenuData;
          $filteredHorizontal->menu = array_values(array_filter(
              $horizontalMenuData->menu,
              fn($item) => !isset($item->roles) || in_array($role, $item->roles)
          ));

          $view->with('menuData', [$filteredVertical, $filteredHorizontal]);
      });
  }
}
