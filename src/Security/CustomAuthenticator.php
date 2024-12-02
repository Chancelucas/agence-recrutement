<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class CustomAuthenticator extends AbstractAuthenticator
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function supports(Request $request): bool
    {
        // Définir les conditions pour lesquelles cet authenticator s'applique
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        // Récupérer les données de connexion du formulaire
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

    
        return new SelfValidatingPassport(new UserBadge($email), []);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Récupération de l'utilisateur connecté
        $user = $token->getUser();

        // Redirection en fonction du rôle de l'utilisateur
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('app_admin_dashboard'));
        }

        return new RedirectResponse($this->router->generate('app_user_dashboard'));
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): RedirectResponse
    {
        // Redirige vers la page de connexion avec un message d'erreur
        return new RedirectResponse($this->router->generate('app_login'));
    }
}
