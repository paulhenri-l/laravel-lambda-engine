<?php

namespace PaulhenriL\LaravelLambdaEngine\Helpers;

use Aws\Sqs\SqsClient;
use Bref\Context\Context;
use Bref\Event\Sqs\SqsEvent;
use Bref\Event\Sqs\SqsHandler;
use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Jobs\SqsJob;
use Throwable;

class SqsQueueHandler extends SqsHandler
{
    protected Container $container;

    protected SqsClient $sqs;

    protected Dispatcher $events;

    protected ExceptionHandler $exceptions;

    protected string $connectionName;

    protected string $queue;

    public function __construct(
        Container $container,
        Dispatcher $events,
        ExceptionHandler $exceptions,
        string $connection,
        string $queue
    ) {
        $this->container = $container;
        $queueManager = $container->get('queue');
        $queueConnector = $queueManager->connection($connection);

        $this->sqs = $queueConnector->getSqs();
        $this->events = $events;
        $this->exceptions = $exceptions;
        $this->connectionName = $connection;
        $this->queue = $queue;
    }

    public function handleSqs(SqsEvent $event, Context $context): void
    {
        foreach ($event->getRecords() as $sqsRecord) {
            $recordData = $sqsRecord->toArray();
            $jobData = [
                'MessageId' => $recordData['messageId'],
                'ReceiptHandle' => $recordData['receiptHandle'],
                'Attributes' => $recordData['attributes'],
                'Body' => $recordData['body'],
            ];

            $job = new SqsJob(
                $this->container,
                $this->sqs,
                $jobData,
                $this->connectionName,
                $this->queue,
            );

            $this->process($this->connectionName, $job);
        }
    }

    protected function process(string $connectionName, SqsJob $job): void
    {
        try {
            $this->raiseBeforeJobEvent($connectionName, $job);

            $job->fire();

            $this->raiseAfterJobEvent($connectionName, $job);
        } catch (Throwable $e) {
            $this->raiseExceptionOccurredJobEvent($connectionName, $job, $e);

            $this->exceptions->report($e);

            $job->release();

            throw $e;
        }
    }

    protected function raiseBeforeJobEvent(string $connectionName, SqsJob $job): void
    {
        $this->events->dispatch(new JobProcessing(
            $connectionName,
            $job
        ));
    }

    protected function raiseAfterJobEvent(string $connectionName, SqsJob $job): void
    {
        $this->events->dispatch(new JobProcessed(
            $connectionName,
            $job
        ));
    }

    protected function raiseExceptionOccurredJobEvent(string $connectionName, SqsJob $job, Throwable $e): void
    {
        $this->events->dispatch(new JobExceptionOccurred(
            $connectionName,
            $job,
            $e
        ));
    }
}

