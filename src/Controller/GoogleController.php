<?php 

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * Route vers l'appel de l'authentification Google
     *
     * 
     */
    #[Route('/connect/google', name: 'connect_google_start')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // Rédirection vers l'authentification Google
        return $clientRegistry
            ->getClient('google') // key créée in config/packages/knpu_oauth2_client.yaml
            ->redirect(['profile', 'email'], []); // les scopes demandés
    }

    /**
     * Apré avoir été redirigé vers Google, on revient ici
     * cette méthode est appelée pour récupérer les informations de l'utilisateur
     * 
     */
    #[Route("/connect/google/check", name:"connect_google_check")]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {    
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        $client = $clientRegistry->getClient('google');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\googleUser $user */
            $user = $client->fetchUser();
            $accessToken = $client->getAccessToken();
            $user = $client->fetchUserFromToken($accessToken);

            dd($user);

            // access the underlying "provider" from league/oauth2-client
            $provider = $client->getOAuth2Provider();
            
            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            // var_dump($user); die;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage()); die;
        }
    }
    
}