<?php

namespace Tests\Unit\App\Services\Admin;

use App\Traits\Services\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Fractal\Fractal;
use Tests\BuckhillBaseTesting;

class AuthServiceTest extends BuckhillBaseTesting
{
    use Auth;

    public function test_auth_login_success()
    {
        DB::table('users')->where('is_admin', '=', 1)->update([
            'password' => Hash::make('admin')
        ]);

        $request = new FormRequest();
        $request->replace([
            'email' => 'admin@buckhill.co.uk',
            'password' => 'admin'
        ]);

        $login = $this->getAuthService()->login($request);

        $this->assertIsArray($login);
        $this->assertNotEmpty($login['token']);
    }

    public function test_auth_login_failure()
    {
        DB::table('users')->where('is_admin', '=', 1)->update([
            'password' => Hash::make('admin')
        ]);

        $request = new FormRequest();
        $request->replace([
            'email' => 'admin@buckhill.co.uk',
            'password' => 'password'
        ]);

        $login = $this->getAuthService()->login($request);

        $this->assertFalse($login);
    }

    public function test_auth_create_user()
    {
        $request = new FormRequest();
        $request->replace([
            'email' => 'test@buckhill.com',
            'password' => 'password',
            'first_name' => 'Test',
            'last_name' => 'User',
            'password_confirmation' => 'password',
            'avatar' => Str::uuid(),
            'address' => '5303 Lubowitz Creek Suite 678 Reingerhaven, ND 62609',
            'phone_number' => '+1.253.273.7280'
        ]);

        $user = $this->getAuthService()->createUser($request);

        $this->assertEquals(Fractal::class, get_class($user));
    }
}
