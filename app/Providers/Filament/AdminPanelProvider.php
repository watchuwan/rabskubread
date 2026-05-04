<?php

namespace App\Providers\Filament;

use App\Enums\NavigationGroup as EnumsNavigationGroup;
use App\Filament\Actions\LanguageSwitcherAction;
use App\Filament\Pages\CustomLogin;
use App\Http\Middleware\SetLocale\SetLocale;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id("admin")
            ->path("admin")
            ->login(CustomLogin::class)
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->colors([
                "primary" => "#F97316",
                "danger" => "#EF4444",
                "gray" => "#6B7280",
                "info" => "#3B82F6",
                "success" => "#10B981",
                "warning" => "#F59E0B",
            ])
            ->font("Inter")
            ->brandName("Toko Roti Admin")
            ->favicon(asset("favicon.ico"))
            ->navigationGroups([
                NavigationGroup::make('Shop')->icon(Heroicon::ShoppingCart),
                NavigationGroup::make('Orders')->icon(Heroicon::ShoppingBag),
                NavigationGroup::make('Customers')->icon(Heroicon::UserGroup),
                NavigationGroup::make('Marketing')->icon(Heroicon::ChartBar),
                NavigationGroup::make('System')->icon(Heroicon::Cog6Tooth),
                NavigationGroup::make('Settings')->icon(Heroicon::AdjustmentsHorizontal),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(
                in: app_path("Filament/Resources"),
                for: "App\\Filament\\Resources",
            )
            ->discoverPages(
                in: app_path("Filament/Pages"),
                for: "App\\Filament\\Pages",
            )
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(
                in: app_path("Filament/Widgets"),
                for: "App\\Filament\\Widgets",
            )
            ->widgets([
                // Widgets\AccountWidget::class,
            ])
            ->userMenuItems([...LanguageSwitcherAction::make()])
            ->resources([RoleResource::class])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationGroup(EnumsNavigationGroup::System)
                    ->gridColumns([
                        "default" => 1,
                        "sm" => 2,
                        "lg" => 3,
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        "default" => 1,
                        "sm" => 2,
                        "lg" => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        "default" => 1,
                        "sm" => 2,
                    ]),
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
