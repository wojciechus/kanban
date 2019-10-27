<?php

namespace App\Authentication;

use App\Environment\EnvironmentResolver;
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
//
//    private
//    function _redirectToGithub()
//    {
//        $url = 'Location: https://github.com/login/oauth/authorize';
//        $url .= '?client_id=' . $this->clientId;
//        $url .= '&scope=repo';
//        $url .= '&state=LKHYgbn776tgubkjhk';
//        header($url);
//        exit();
//    }
//
//    private function _returnsFromGithub($code)
//    {
//        $url = 'https://github.com/login/oauth/access_token';
//        $data = array(
//            'code' => $code,
//            'state' => 'LKHYgbn776tgubkjhk',
//            'client_id' => $this->clientId,
//            'client_secret' => $this->clientSecret);
//        $options = array(
//            'http' => array(
//                'method' => 'POST',
//                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
//                'content' => http_build_query($data),
//            ),
//        );
//        $context = stream_context_create($options);
//        $result = file_get_contents($url, false, $context);
//        if ($result === FALSE)
//            die('Error');
//        $result = explode('=', explode('&', $result)[0]);
//        array_shift($result);
//
//        return array_shift($result);
//    }
}
