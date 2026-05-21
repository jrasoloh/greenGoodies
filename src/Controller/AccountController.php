<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function toggleApi(EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $newState = !$user->isApiKeyActive();
        $user->setIsApiKeyActive($newState);

        if ($newState && !$user->getApiKey()) {
            $user->setApiKey(bin2hex(random_bytes(16)));
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_account_index');
    }

    #[Route('/delete', name: 'delete', methods: ['POST'])]
    public function deleteAccount(EntityManagerInterface $entityManager, Security $security, Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if ($this->isCsrfTokenValid('delete-account', $request->request->get('_token'))) {
            $security->logout(false);

            $entityManager->remove($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_account_index');
    }
}
