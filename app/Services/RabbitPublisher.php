<?php

namespace App\Services;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Ramsey\Uuid\Uuid;

class RabbitPublisher
{
    private AMQPStreamConnection $conn;
    private AMQPChannel $ch;
    private string $exchange;

    public function __construct()
    {
        $host  = config('queue.connections.rabbitmq.hosts.0.host', env('RABBITMQ_HOST', 'rabbitmq'));
        $port  = (int) config('queue.connections.rabbitmq.hosts.0.port', env('RABBITMQ_PORT', 5672));
        $user  = config('queue.connections.rabbitmq.hosts.0.user', env('RABBITMQ_USER', 'guest'));
        $pass  = config('queue.connections.rabbitmq.hosts.0.password', env('RABBITMQ_PASSWORD', 'guest'));
        $vhost = config('queue.connections.rabbitmq.hosts.0.vhost', env('RABBITMQ_VHOST', '/'));

        $this->exchange = env('RABBITMQ_EVENTS_EXCHANGE', 'app.events');

        $this->conn = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
        $this->ch   = $this->conn->channel();

        $this->ch->exchange_declare($this->exchange, 'topic', false, true, false);

        $this->ch->confirm_select();
    }

    /**
     * @param string $eventType
     * @param array $payload
     * @param array $meta
     */
    public function publish(string $eventType, array $payload, array $meta = []): void
    {
        $eventId   = Uuid::uuid4()->toString();
        $occurred  = now()->toImmutable()->utc();
        $appId     = config('app.name', 'laravel-api');
        $version   = 1;

        $idempotency = $payload['transfer_id'] ?? $payload['id'] ?? $eventId;
        $idempotency .= '|' . $occurred->getTimestamp();

        $body = [
            'event_id'       => $eventId,
            'event_type'     => $eventType,
            'event_version'  => $version,
            'occurred_at'    => $occurred->toIso8601String(),
            'producer'       => $appId,
            'idempotency_key'=> $idempotency,
            'data'           => $payload,
            'meta'           => $meta,
        ];

        $msg = new AMQPMessage(
            json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            [
                'content_type'  => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'message_id'    => $eventId,
                'timestamp'     => $occurred->getTimestamp(),
                'type'          => $eventType,
                'app_id'        => $appId,
                'application_headers' => new AMQPTable([
                    'x-idempotency-key' => $idempotency,
                    'x-event-version'   => $version,
                ]),
            ]
        );

        $this->ch->basic_publish($msg, $this->exchange, $eventType, true);

        $this->ch->wait_for_pending_acks_returns(5.0);
    }

    public function __destruct()
    {
        try { $this->ch->close(); } catch (\Throwable) {}
        try { $this->conn->close(); } catch (\Throwable) {}
    }
}
