<?php

namespace App\Providers\Filament;

use App\Filament\Resources\AccommodationResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\ReservationResource;
use App\Filament\Resources\ReservationResource\Widgets\MultiOverview;
use App\Filament\Resources\ReservationResource\Widgets\ReservationChart;
use App\Filament\Resources\ReservationResource\Widgets\ReservationOverview;
use App\Filament\Resources\ReservationResource\Widgets\StatsOverview;
use App\Filament\Resources\StaffResource;
use App\Models\Customer;
use App\Providers\AppCustomVersionProvider;
use Awcodes\FilamentVersions\Providers\LaravelVersionProvider;
use Awcodes\FilamentVersions\Providers\PHPVersionProvider;
use Awcodes\FilamentVersions\VersionsPlugin;
use Awcodes\Overlook\OverlookPlugin;
use Awcodes\Overlook\Widgets\OverlookWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
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
            ->brandLogo(asset('images/vc-logo.png'))
            ->favicon(asset('images/vc-logo.png'))
            ->sidebarCollapsibleOnDesktop()
            ->brandName(' V&C HRS')
            ->default()
            ->passwordReset()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#f7b416') ,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,

            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
//                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
                OverlookWidget::class,
//                ReservationOverview::make(),
//                ReservationChart::make(),
                MultiOverview::make(),


            ])
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
            ->databaseNotifications()
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugins([
                VersionsPlugin::make()
                    ->hasDefaults(false)
                    ->items([
                        new AppCustomVersionProvider(),
                        new LaravelVersionProvider(),
                        new PHPVersionProvider(),
                    ]),


                OverlookPlugin::make()
//                    ->sort(1)
                    ->columns([
                        'default' => 1,
                        'sm' => 1,
                        'md' => 2,
                        'lg' => 2,
                        'xl' => 4,
                        '2xl' => null,
                    ])

                    ->includes([
                        ReservationResource::class,
                        CustomerResource::class,
                        AccommodationResource::class,
                        StaffResource::class,

                    ]),



            ]);
    }
}
