<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRabbitMQQueues extends Command
{
    protected $signature = 'rabbit:provision:defaults';
    protected $description = 'Provision queues';

    private const ENV_PREFIX   = 'dev';
    private const SERVICE      = 'ledger';
    private const EXCHANGE     = 'app.events';
    private const WITH_RETRY   = true;
    private const RETRY_TTL_MS = 30000;

    public function handle(): int
    {
        $targets = [
            [
                'context'  => 'payments',
                'purpose'  => 'transfers',
                'bindings' => 'payments.transfers.*',
            ],
            [
                'context'  => 'accounts',
                'purpose'  => 'users',
                'bindings' => 'accounts.users.*',
            ],
        ];

        foreach ($targets as $t) {
            $queueName = sprintf(
                '%s.%s.%s.%s',
                self::ENV_PREFIX,
                self::SERVICE,
                $t['context'],
                $t['purpose']
            );

            $this->info("Provisionando: {$queueName}");

            $params = [
                'service'       => self::SERVICE,
                'context'       => $t['context'],
                'purpose'       => $t['purpose'],
                '--bindings'    => $t['bindings'],
                '--env-prefix'  => self::ENV_PREFIX,
                '--exchange'    => self::EXCHANGE,
            ];

            if (self::WITH_RETRY) {
                $params['--with-retry'] = true;
                $params['--retry-ttl']  = self::RETRY_TTL_MS;
            }

            $exit = $this->call('rabbit:provision', $params);
            if ($exit !== 0) {
                $this->error("Failed when tried to create {$queueName}");
                return $exit;
            }
        }

        $this->info('Created queues for contexts: '.implode(', ', array_column($targets, 'context')));

        return self::SUCCESS;
    }
}
