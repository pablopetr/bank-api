<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;

class RabbitProvision extends Command
{
    protected $signature = 'rabbit:provision
        {service=ledger}
        {context=payments}
        {purpose=extrato-writer}
        {--bindings=payments.*.*,wallets.transactions.*}
        {--env-prefix=dev}
        {--exchange=app.events}
        {--with-retry}
        {--retry-ttl=30000}';

    protected $description = 'Provisiona exchanges, filas e bindings no RabbitMQ';

    public function handle(): int
    {
        $host = config('queue.connections.rabbitmq.hosts.0.host', env('RABBITMQ_HOST', 'rabbitmq'));
        $port = (int) env('RABBITMQ_PORT', 5672);
        $user = env('RABBITMQ_USER', 'guest');
        $pass = env('RABBITMQ_PASSWORD', 'guest');
        $vhost = env('RABBITMQ_VHOST', '/');

        $exchange = $this->option('exchange');
        $env = $this->option('env-prefix');
        $service = $this->argument('service');
        $context = $this->argument('context');
        $purpose = $this->argument('purpose');

        $queue = "{$env}.{$service}.{$context}.{$purpose}";
        $retryQ = "{$queue}.retry";
        $dlq = "{$queue}.dlq";

        $conn = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
        $ch = $conn->channel();

        $ch->exchange_declare($exchange, 'topic', false, true, false);
        $ch->exchange_declare('app.dlx', 'direct', false, true, false);

        $ch->queue_declare($dlq, false, true, false, false);

        if ($this->option('with-retry')) {
            $ch->queue_declare($retryQ, false, true, false, false, false, new AMQPTable([
                'x-dead-letter-exchange' => '',
                'x-dead-letter-routing-key' => $queue,
                'x-message-ttl' => (int) $this->option('retry-ttl'),
            ]));
        }

        $ch->queue_declare($queue, false, true, false, false, false, new AMQPTable([
            'x-dead-letter-exchange' => 'app.dlx',
            'x-dead-letter-routing-key' => $dlq,
        ]));

        $ch->queue_bind($dlq, 'app.dlx', $dlq);

        $bindings = array_filter(array_map('trim', explode(',', $this->option('bindings'))));
        foreach ($bindings as $rk) {
            $ch->queue_bind($queue, $exchange, $rk);
        }

        $ch->close();
        $conn->close();

        $this->info("OK: exchange={$exchange}, queue={$queue}, retry={$retryQ}, dlq={$dlq}");
        $this->info('Bindings: '.implode(', ', $bindings));

        return self::SUCCESS;
    }
}
