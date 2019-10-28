<?php

namespace App\Authentication;

use App\Environment\EnvironmentResolver;
use App\Services\ArrayChecker;

class Authentication
{

    private $clientId = NULL;
    private $clientSecret = NULL;
    private $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->clientId = EnvironmentResolver::env('GH_CLIENT_ID');
        $this->clientSecret = EnvironmentResolver::env('GH_CLIENT_SECRET');
        $this->tokenService = $tokenService;
    }

    public function logout(): void
    {
        unset($_SESSION['gh-token']);
    }

    public function login(): ?string
    {
        session_start();

        $token = NULL;

        if ($this->tokenService->isTokenInSession()) {
            return $this->tokenService->getTokenFromSession();
        }

        if ($this->tokenService->isRedirectedFromGithub()) {
            $this->tokenService->setShouldRedirect(false);
            $token = $this->returnsFromGithub($_GET['code']);
        } else {
            $this->tokenService->setShouldRedirect(true);
            $this->redirectToGithub();
        }
        $this->logout();
        $_SESSION['gh-token'] = $token;

        return $token;
    }

    private function redirectToGithub()
    {
        $url = 'Location: https://github.com/login/oauth/authorize';
        $url .= '?client_id=' . $this->clientId;
        $url .= '&scope=repo';
        $url .= '&state=LKHYgbn776tgubkjhk';
        header($url);
        exit();
    }

    private function returnsFromGithub($code)
    {
        $url = 'https://github.com/login/oauth/access_token';
        $data = [
            'code' => $code,
            'state' => 'LKHYgbn776tgubkjhk',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ];
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
            ],
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE)
            die('Error');
        $result = explode('=', explode('&', $result)[0]);

        array_shift($result);

        return array_shift($result);
    }
}
