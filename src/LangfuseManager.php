<?php

namespace Langfuse;

use Langfuse\Client\LangfuseClient;

class LangfuseManager
{

    public function __construct(
        private LangfuseClient $langfuseClient
    )
    {}

    public function withTrace(string $name, array $metadata, callable $callback): mixed
    {
        $this->langfuseClient->startTrace($name, $metadata);
        $result = $callback();
        $this->langfuseClient->endTrace();

        return $result;
    }

    public function withGeneration(
        string $name,
        string $modelName,
        array $prompt,
        callable $callback
    ): mixed
    {
        $event = $this->langfuseClient->startGeneration($name, $modelName, $prompt);
        $result = $callback();
        $event->setOutput($result);
        $this->langfuseClient->endGeneration($event);

        return $result;
    }

}
