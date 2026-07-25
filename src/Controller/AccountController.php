<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\AccountManager;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/account', name: 'app_account_')]
class AccountController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('account/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @throws RandomException
     */
    #[Route('/api/toggle', name: 'api_toggle', methods: ['POST'])]
    public function toggleApi(AccountManager $accountManager, Security $security): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $accountManager->toggleApiKey($user);

        // Les rôles de l'utilisateur changent (ROLE_API ajouté/retiré) : on rafraîchit
        // le token de sécurité pour éviter que Symfony n'invalide la session (déconnexion).
        $security->login($user, firewallName: 'main');

        return $this->redirectToRoute('app_account_index');
    }

    #[Route('/delete', name: 'delete', methods: ['POST'])]
    public function deleteAccount(AccountManager $accountManager, Security $security, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
            return $this->redirectToRoute('app_account_index');
        }

        $accountManager->deleteAccount($user);

        $security->logout(false);

        $response = $this->redirectToRoute('app_home');
        $response->headers->clearCookie('REMEMBERME', '/');

        return $response;
    }
}
