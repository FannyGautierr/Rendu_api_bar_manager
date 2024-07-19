<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Order;
use App\Enum\OrderStatus;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;


class OrderProcessor implements ProcessorInterface
{
    private $security;
    private ProcessorInterface $persistProcessor;

    public function __construct(
        Security $security,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        ProcessorInterface $persistProcessor
    ) {
        $this->security = $security;
        $this->persistProcessor = $persistProcessor;
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        // Check if the request object is available in the context
        if (isset($context['request']) && $context['request'] instanceof Request) {
            $request = $context['request'];
            $method = $request->getMethod();
            $order = $data;

            if ($data instanceof Order) {
                $user = $this->security->getUser();
                if ($method === Request::METHOD_POST) {
                    $data->setWaiter($user);
                } elseif ($method === Request::METHOD_PATCH) {
                   if($order->getStatus() === OrderStatus::Paid){
                    throw new Exception('Order already paid');
                   }
                }
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
