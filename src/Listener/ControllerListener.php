<?php
namespace App\EventListener;

use App\Entity\User;
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
            $newUser = $this->createGuestUser();
            $this->loginUser($newUser);
        }
    }

    private function createGuestUser(): User
    {
        $newUser = $this->userFactory->createGuestUser();
        $this->entityManager->persist($newUser);
        $this->entityManager->flush();

        return $newUser;
    }

    private function loginUser(User $user): void
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
        $this->session->set('_security_main', \serialize($token));
}
}
