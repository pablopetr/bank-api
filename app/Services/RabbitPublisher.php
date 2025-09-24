<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitPublisher
{
    public function __construct(
        private ?string $host = null,
        private ?int    $port = null,
        private ?string $user = null,
        private ?string $pass = null,
        private ?string $vhost = null,
        private ?string $exchange = null,
    ) {
        $this->host = $this->host ?? config('queue.connections.rabbitmq.hosts.0.host', env('RABBITMQ_HOST', 'rabbitmq'));
        $this->port = $this->port ?? (int) config('queue.connections.rabbitmq.hosts.0.port', env('RABBITMQ_PORT', 5672));
        $this->user = $this->user ?? config('queue.connections.rabbitmq.hosts.0.user', env('RABBITMQ_USER', 'guest'));
        $this->pass = $this->pass ?? config('queue.connections.rabbitmq.hosts.0.password', env('RABBITMQ_PASSWORD', 'guest'));
        $this->vhost = $this->vhost ?? config('queue.connections.rabbitmq.hosts.0.vhost', env('RABBITMQ_VHOST', '/'));
        $this->exchange = $this->exchange ?? config('queue.connections.rabbitmq.options.exchange.name', env('RABBITMQ_EXCHANGE', 'app.events'));
    }

    public function publish(string $routingKey, array $payload): void
    {
        $conn = new AMQPStreamConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $ch = $conn->channel();

        $ch->exchange_declare($this->exchange, 'topic', false, true, false);

        $msg = new AMQPMessage(
            json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            ['content_type' => 'application/json', 'delivery_mode' => 2] // 2 = persistente
        );

        $ch->basic_publish($msg, $this->exchange, $routingKey);

        $ch->close();

        $conn->close();
    }
}
