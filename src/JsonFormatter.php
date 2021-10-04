<?php

namespace PaulhenriL\LaravelLambdaEngine;

class JsonFormatter extends \Monolog\Formatter\JsonFormatter
{
    public function format(array $record): string
    {
        $record['context'] = array_merge(
            $record['context'] ?? [],
            ['aws_request_id' => $this->getRequestId()],
        );

        return parent::format($record);
    }

    protected function getRequestId(): ?string
    {
        $context = json_decode(
            $_ENV['LAMBDA_INVOCATION_CONTEXT'] ?? '[]',
            true
        );

        return $context['awsRequestId'] ?? null;
    }
}
