<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitProvision extends Command
{
    protected $signature = 'rabbit:provision
        {queue=dev.sink.api.users.registered}
        {routingKey=api.users.registered}
        {exchange=app.events}';

    protected $description = 'Declara exchange, fila e binding no RabbitMQ';

    public function handle()
    {
        $host = config('queue.connections.rabbitmq.hosts.0.host', env('RABBITMQ_HOST', 'rabbitmq'));
        $port = (int)config('queue.connections.rabbitmq.hosts.0.port', env('RABBITMQ_PORT', 5672));
        $user = config('queue.connections.rabbitmq.hosts.0.user', env('RABBITMQ_USER', 'guest'));
        $pass = config('queue.connections.rabbitmq.hosts.0.password', env('RABBITMQ_PASSWORD', 'guest'));
        $vhost= config('queue.connections.rabbitmq.hosts.0.vhost', env('RABBITMQ_VHOST', '/'));

        $exchange = $this->argument('exchange');
        $queue    = $this->argument('queue');
        $rk       = $this->argument('routingKey');

        $conn = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
        $ch   = $conn->channel();

        // garante exchange topic durável
        $ch->exchange_declare($exchange, 'topic', false, true, false);

        // garante fila durável
        $ch->queue_declare($queue, false, true, false, false);

        // binding exchange -> fila com routing key
        $ch->queue_bind($queue, $exchange, $rk);

        $ch->close();
        $conn->close();

        $this->info("OK: exchange={$exchange} (topic), queue={$queue}, binding rk={$rk}");
        return self::SUCCESS;
    }
}
