<?php

declare(strict_types=1);

namespace Chebur\RabbitMqConsumerDecoratorNewRelic;

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
    private $newRelic;

    /**
     * @var string
     */
    private $applicationName;

    /**
     * @var string
     */
    private $transactionName;

    public function __construct(
        ConsumerInterface $consumer,
        NewRelicInteractorInterface $newRelic,
        string $applicationName,
        string $transactionName
    ) {
        $this->consumer = $consumer;
        $this->newRelic = $newRelic;
        $this->applicationName = $applicationName;
        $this->transactionName = $transactionName;
    }

    public function execute(AMQPMessage $msg)
    {
        $this->newRelic->startTransaction($this->applicationName);
        $this->newRelic->setTransactionName($this->transactionName);
        $flag = $this->consumer->execute($msg);
        $this->newRelic->endTransaction();

        return $flag;
    }
}
