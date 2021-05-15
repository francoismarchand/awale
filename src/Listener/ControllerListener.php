<?php
namespace App\EventListener;

use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ControllerListener
{
    private $entityManager;
    private $security;
    private $session;
    private $tokenStorage;
    private $userFactory;    

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        UserFactory $userFactory

    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;        
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->userFactory = $userFactory;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $user = $this->security->getUser();

        if (null === $user) {
            $newUser = $this->userFactory->createGuestUser();
            $this->entityManager->persist($newUser);
            $this->entityManager->flush();

            $token = new UsernamePasswordToken($newUser, null, 'main', $newUser->getRoles());
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_main', \serialize($token));

            //TODO BUG l'ustilisateur n'est pas loggé en session
        }
    }
}
