<?php

namespace PaulhenriL\LaravelLambdaEngine\Log;

class JsonFormatter extends \Monolog\Formatter\JsonFormatter
{
    public function format(array $record): string
    {
        $extraContext = [
            'aws_request_id' => json_decode($_ENV['LAMBDA_INVOCATION_CONTEXT'] ?? '[]', true)['awsRequestId'] ?? null,
        ];

        $record['context'] = array_merge(
            $record['context'] ?? [],
            $extraContext
        );

        return parent::format($record);
    }
}
