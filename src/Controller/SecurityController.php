<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si déjà connecté, rediriger en fonction des rôles
        if ($this->getUser()) {
            return $this->redirectToRoute($this->getUser()->getRoles()[0] === 'ROLE_ADMIN' ? 'app_admin_dashboard' : 'app_user_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    private function getRedirectRoute(): string
    {
        $roles = $this->getUser()->getRoles();


        if (in_array('ROLE_ADMIN', $roles)) {
            return 'app_admin_dashboard';
        } elseif (in_array('ROLE_USER', $roles)) {
            return 'app_user_dashboard';
        } else {
            return 'app_home';
        }
    }
}
