<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use Modules\Common\Application\ReadCacheUserPermission;
use Modules\Common\Permissions\PermissionManager;
use Modules\Settings\Applications\ReadSettingUserCase;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * @param PermissionManager $permissionManager
     */
    public function __construct(
        private readonly PermissionManager $permissionManager
    )
    {

    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $readCacheUserPermission = new ReadCacheUserPermission;
        $user = $request->user();
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
                'can' => $readCacheUserPermission($user,$this->permissionManager->getAuthUserPermissions())
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'notification' => Session::get('notification')
            ],
            'settings' => setting()->all(),
            'redirect_data' => fn () => $request->session()->get('redirect_data')
        ]);
    }
}
