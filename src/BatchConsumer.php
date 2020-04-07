<?php

declare(strict_types=1);

namespace Chebur\RabbitMqConsumerDecoratorNewRelic;

use Ekino\NewRelicBundle\NewRelic\NewRelicInteractorInterface;
use OldSound\RabbitMqBundle\RabbitMq\BatchConsumerInterface;

class BatchConsumer implements BatchConsumerInterface
{
    /**
     * @var BatchConsumerInterface
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
        BatchConsumerInterface $consumer,
        NewRelicInteractorInterface $newRelic,
        string $applicationName,
        string $transactionName
    ) {
        $this->consumer = $consumer;
        $this->newRelic = $newRelic;
        $this->applicationName = $applicationName;
        $this->transactionName = $transactionName;
    }

    public function batchExecute(array $messages)
    {
        $this->newRelic->startTransaction($this->applicationName);
        $this->newRelic->setTransactionName($this->transactionName);
        $result = $this->consumer->batchExecute($messages);
        $this->newRelic->endTransaction();

        return $result;
    }
}
