<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make(__('Products'))
                ->icon('bs.bag')
                ->title(__('Content Manager'))
                ->route('platform.product.list'),

            Menu::make(__('Sales'))
                ->icon('bs.percent')
                ->sort(2),

            Menu::make(__('Categories'))
                ->icon('bs.collection')
                ->route('platform.category.list')
                ->sort(2),

            Menu::make(__('Tags'))
                ->icon('bs.tags')
//                ->route('platform.category.list')
                ->sort(2),

            Menu::make(__('Banners'))
                ->icon('bs.easel2'),

            Menu::make(__('Articles'))
                ->icon('bs.journal-text')
                ->divider(),

            Menu::make(__('Settings'))
                ->icon('bs.gear')
                ->title(__('Site Settings')),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            Menu::make('Example elements')
                ->icon('bs.archive')
                ->list([
                    Menu::make('Get Started')
                        ->icon('bs.book')
                        ->title('Navigation')
                        ->route(config('platform.index')),

                    Menu::make('Sample Screen')
                        ->icon('bs.collection')
                        ->route('platform.example')
                        ->badge(fn () => 6),

                    Menu::make('Form Elements')
                        ->icon('bs.card-list')
                        ->route('platform.example.fields')
                        ->active('*/examples/form/*'),

                    Menu::make('Overview Layouts')
                        ->icon('bs.window-sidebar')
                        ->route('platform.example.layouts'),

                    Menu::make('Grid System')
                        ->icon('bs.columns-gap')
                        ->route('platform.example.grid'),

                    Menu::make('Charts')
                        ->icon('bs.bar-chart')
                        ->route('platform.example.charts'),

                    Menu::make('Cards')
                        ->icon('bs.card-text')
                        ->route('platform.example.cards')
                        ->divider(),
                ])
                ->divider(),

            Menu::make('Documentation')
                ->title('Docs')
                ->icon('bs.box-arrow-up-right')
                ->url('https://orchid.software/en/docs')
                ->target('_blank'),

            Menu::make('Changelog')
                ->icon('bs.box-arrow-up-right')
                ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                ->target('_blank')
                ->badge(fn () => Dashboard::version(), Color::DARK),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
