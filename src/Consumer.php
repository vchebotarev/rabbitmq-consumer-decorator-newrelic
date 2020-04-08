<?php

declare(strict_types=1);

namespace Chebur\RabbitMqConsumerDecoratorNewRelic;

use Ekino\NewRelicBundle\NewRelic\Config as NewRelicConfig;
use Ekino\NewRelicBundle\NewRelic\NewRelicInteractorInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer implements ConsumerInterface
{
    /**
     * @var ConsumerInterface
     */
    private $consumer;

    /**
     * @var NewRelicInteractorInterface
     */
    private $newRelicInteractor;

    /**
     * @var NewRelicConfig
     */
    private $newRelicConfig;

    /**
     * @var string
     */
    private $transactionName;

    public function __construct(
        ConsumerInterface $consumer,
        NewRelicInteractorInterface $newRelicInteractor,
        NewRelicConfig $newRelicConfig,
        string $transactionName
    ) {
        $this->consumer = $consumer;
        $this->newRelicInteractor = $newRelicInteractor;
        $this->newRelicConfig = $newRelicConfig;
        $this->transactionName = $transactionName;
    }

    public function execute(AMQPMessage $message)
    {
        $this->newRelicInteractor->startTransaction($this->newRelicConfig->getName());
        $this->newRelicInteractor->setTransactionName($this->transactionName);
        $flag = $this->consumer->execute($message);
        $this->newRelicInteractor->endTransaction();

        return $flag;
    }
}
