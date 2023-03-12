<?php

namespace src\Connection;

class Connection
{
    public int $delay = 0;
    protected string $driver = DRIVER;
    protected string $channel;
    private int $max_priority = MAX_PRIORITY;

    public function connection($channel = null)
    {
        $channel = $this->channel ?: $channel;
    }
}