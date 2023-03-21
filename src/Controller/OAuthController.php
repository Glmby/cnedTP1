<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OAuthController extends AbstractController
{
/**
 * @Route("/oauth/login", name="oauth_login")
 * @param ClientRegistry $clientRegistry
 * @return RedirectResponse
 */
    public function index(ClientRegistry $clientRegistry): RedirectResponse 
    {
        return $clientRegistry->getClient('keycloak')->redirect();
    }
    /**
     * @Route("/oauth/callback", name="oauth_check")
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry){
        
    }
}
