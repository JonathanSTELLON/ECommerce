<?php

namespace App\Security;

use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator{

    use TargetPathTrait;

    public const LOGIN_ROUTE = 'security_login';
    private $urlGenerator;
    private $formFactory;
    private $flashbag;

    public function __construct(UrlGeneratorInterface $urlGenerator, FormFactoryInterface $formFactory, FlashBagInterface $flashbag){

        $this->urlGenerator = $urlGenerator;
        $this->formFactory = $formFactory;
        $this->flashbag = $flashbag;
    }

    public function getLoginUrl(Request $request):string{

        return $this->urlGenerator->generate(self::LOGIN_ROUTE);

    }

    public function authenticate(Request $request): PassportInterface{

        //1 Récupérer les identifiants de connexion
            //On recrée le formulaire
            $form = $this->formFactory->create(LoginType::class);
            $form->handleRequest($request);
            //On récupère les données
            $data = $form->getData();
            $email = $data['email'];
            $plainPassword = $data['plainPassword'];

        //2 Enregistrer l'email en session
            $request->getSession()->set(Security::LAST_USERNAME, $email);

        //3 Créer un passport
            $badge = new UserBadge($email);
            $credentials = new PasswordCredentials($plainPassword);

        return new Passport($badge, $credentials,[
            new CsrfTokenBadge('authenticate', $request->get('login')['_csrf_token'])
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response{

        // $targetPath est l'url de la page ou l'internaute voulait aller, si jamais il faut se connecter pour accéder à une page il sera renvoyé sur cette page ensuite une fois connecté
        if($targetPath = $this->getTargetPath($request->getSession(), $firewallName)){
            return new RedirectResponse($targetPath);
        }

        /**
         * @var User
         */
        $user = $token->getUser(); //pr afficher sur le template le nom de l'user

        // 1 Ajouter un message flash
        $this->flashbag->add('success', 'Bienvenue ' .$user->getFullName(). ' !');

        // 2 Redirection vers home si user ou vers admin si le user a le rôle admin
        if ($user->isAdmin() == false){
            return new RedirectResponse($this->urlGenerator->generate('home_index'));
        }
        else{
            return new RedirectResponse($this->urlGenerator->generate('admin_index'));
        }
        
    }

}