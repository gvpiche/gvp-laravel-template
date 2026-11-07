<?php

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

test('guest starter pages render without JavaScript errors', function () {
    visit(['/login', '/register', '/forgot-password', '/reset-password/test-token?email=test@example.com'])
        ->assertNoJavaScriptErrors();
});

test('users can log in using the Inertia form', function () {
    $user = User::factory()->create();

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'password')
        ->press('Log in')
        ->assertPathIs('/dashboard')
        ->assertNoJavaScriptErrors();

    $this->assertAuthenticatedAs($user);
});

test('profile updates show a typed Inertia flash toast', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    visit('/settings/profile')
        ->fill('name', 'Updated User')
        ->press('Save')
        ->assertSee('Profile updated.')
        ->assertNoJavaScriptErrors();

    expect($user->fresh()->name)->toBe('Updated User');
});

test('the two factor challenge updates its layout when switching to a recovery code', function () {
    $user = User::factory()->withTwoFactor()->create();

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'password')
        ->press('Log in')
        ->assertSee('Authentication code')
        ->click('login using a recovery code')
        ->assertSee('Recovery code')
        ->fill('recovery_code', 'recovery-code-1')
        ->press('Continue')
        ->assertPathIs('/dashboard')
        ->assertNoJavaScriptErrors();

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->recoveryCodes())->not->toContain('recovery-code-1');
});

test('two factor setup loads its QR code and secret and can regenerate recovery codes', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $page = visit('/settings/security')
        ->fill('password', 'password')
        ->press('Confirm password')
        ->assertPathIs('/settings/security')
        ->press('Enable 2FA')
        ->assertSee('or, enter the code manually')
        ->assertPresent('[role="dialog"] svg path')
        ->assertValue('[role="dialog"] input[readonly]', decrypt($user->fresh()->two_factor_secret))
        ->press('Continue');

    $code = app(Google2FA::class)->getCurrentOtp(decrypt($user->fresh()->two_factor_secret));

    $page->fill('code', $code)
        ->press('Confirm')
        ->assertSee('Disable 2FA')
        ->press('View recovery codes')
        ->assertCount('[aria-label="Recovery codes"] [role="listitem"]', 8);

    $originalCodes = $user->fresh()->recoveryCodes();

    $page->press('Regenerate codes')
        ->assertDontSee($originalCodes[0])
        ->assertSee($user->fresh()->recoveryCodes()[0])
        ->assertNoJavaScriptErrors();

    expect($user->fresh()->two_factor_confirmed_at)->not->toBeNull()
        ->and($user->fresh()->recoveryCodes())->not->toBe($originalCodes);
});
