<?php

namespace App\Authentication;

use App\Services\ArrayChecker;

class TokenService
{
    private const TOKEN_KEY = 'gh-token';

    public function isTokenInSession(): bool
    {
        return array_key_exists(self::TOKEN_KEY, $_SESSION);
    }

    public function getTokenFromSession(): string
    {
        return $_SESSION[self::TOKEN_KEY];
    }

    public function isRedirectedFromGithub(): bool
    {
        return ArrayChecker::hasValue($_GET, 'code')
            && ArrayChecker::hasValue($_GET, 'state')
            && $_SESSION['redirected'];
    }

    public function setShouldRedirect(bool $redirected): void
    {
        $_SESSION['redirected'] = $redirected;
    }
}
