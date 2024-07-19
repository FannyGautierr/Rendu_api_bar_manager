<?php
 
namespace App\State;
 
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserPasswordHasherProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private UserPasswordHasherInterface $passwordHasher,
        private Security $security
    ) {
    }
 
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // if ($data instanceof User && $data->getId() != null && $data != $this->security->getUser()) {
        //     throw new AccessDeniedException('Not allowed to edit password.');
        // }

        $roles = $data->getRoles()[0];
        $rolesList = [
            'ROLE_ADMIN',
            'ROLE_BOSS',
            'ROLE_USER',
            'ROLE_WAITER',
            'ROLE_BARMAN'
        ];
        
        if(!in_array($roles, $rolesList)) {
            throw new Exception('Please enter a valid role.');
        }

        if (!$data->getPlainPassword()) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $data,
            $data->getPlainPassword()
        );
        $data->setPassword($hashedPassword);
        $data->eraseCredentials();
 
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}