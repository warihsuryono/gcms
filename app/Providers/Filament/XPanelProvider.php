<?php

namespace App\Providers\Filament;

use Exception;
use Filament\Pages;
use Filament\Panel;
use App\Models\menu;
use Filament\Widgets;
use App\Models\Privilege;
use Pest\Plugins\Profile;
use Filament\PanelProvider;
use App\Livewire\ProfileWidget;
use App\Livewire\DashboardWidget;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Session;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use Illuminate\Support\Facades\Exceptions;
use App\Filament\Pages\StoreAcademyMaterial;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class XPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            $navigation_groups = [];
            $navigations = [];
            $privileges = Privilege::find(Auth::user()->privilege_id);
            $menu_ids = explode(",", $privileges->menu_ids);

            if (Auth::user()->privilege_id == 1) $mainmenus = menu::where("parent_id", "0")->orderBy('seqno', 'asc')->get();
            else $mainmenus = menu::where("parent_id", "0")->whereIn('id', $menu_ids)->orderBy('seqno', 'asc')->get();

            foreach ($mainmenus as $mainmenu) {
                if (Auth::user()->privilege_id == 1) $childmenus = menu::where("parent_id", $mainmenu->id)->orderBy('seqno', 'asc')->get();
                else  $childmenus = menu::where("parent_id", $mainmenu->id)->whereIn('id', $menu_ids)->orderBy('seqno', 'asc')->get();

                if (count($childmenus) == 0) { // ga punya child
                    try {
                        $builder->items([
                            NavigationItem::make($mainmenu->name)
                                ->url(App::make('url')->to(env('PANEL_PATH') . '/' . $mainmenu->url))
                                ->icon($mainmenu->icon)
                        ]);
                    } catch (Exception $e) {
                    }
                } else {
                    foreach ($childmenus as $childmenu) {
                        array_push(
                            $navigations,
                            NavigationItem::make($childmenu->name)
                                ->url(App::make('url')->to(env('PANEL_PATH') . "/" . $childmenu->url))
                                ->group($mainmenu->name)
                        );
                    }
                    array_push(
                        $navigation_groups,
                        NavigationGroup::make()
                            ->label($mainmenu->name)
                            ->icon($mainmenu->icon)
                            ->items($navigations)
                    );
                    $builder->groups($navigation_groups);
                    $navigation_groups = [];
                    $navigations = [];
                }
            }
            return $builder;
        });

        $panel
            ->default()
            ->id(env('PANEL_PATH'))
            ->path(env('PANEL_PATH'))
            ->favicon(request()->segment(0) . '/img/favicon.png')
            ->brandLogo(request()->segment(0) . '/img/logo.png')
            ->brandLogoHeight('50px')
            ->userMenuItems([
                'profile' => MenuItem::make()->url(App::make('url')->to(env('PANEL_PATH') . '/profiles')),
            ])
            ->databaseNotifications()
            ->login()
            ->passwordReset()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                ProfileWidget::class,
                DashboardWidget::class,
            ])
            ->sidebarFullyCollapsibleOnDesktop()
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn() => view('guestlink')
            )
            ->renderHook(
                PanelsRenderHook::FOOTER,
                fn() => view('footer')
            )
            ->renderHook(
                PanelsRenderHook::CONTENT_END,
                fn() => view('endcontent')
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

        return $panel;
    }
}
