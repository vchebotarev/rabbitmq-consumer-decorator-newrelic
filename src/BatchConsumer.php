<?php

declare(strict_types=1);

namespace Chebur\RabbitMqConsumerDecoratorNewRelic;

use Ekino\NewRelicBundle\NewRelic\Config as NewRelicConfig;
use Ekino\NewRelicBundle\NewRelic\NewRelicInteractorInterface;
use OldSound\RabbitMqBundle\RabbitMq\BatchConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;

class BatchConsumer implements BatchConsumerInterface
{
    /**
     * @var BatchConsumerInterface
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

    public function batchExecute(array $messages)
    {
        $this->newRelicInteractor->startTransaction($this->newRelicConfig->getName());
        $this->newRelicInteractor->setTransactionName($this->transactionName);
        $result = $this->consumer->batchExecute($messages);
        $this->newRelicInteractor->endTransaction();

        return $result;
    }
}
