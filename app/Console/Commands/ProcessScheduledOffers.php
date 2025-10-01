<?php

namespace App\Console\Commands;

use App\Services\ScheduledOfferService;
use Illuminate\Console\Command;

class ProcessScheduledOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:process-scheduled {--limit=50 : Número máximo de agendamentos processados por execução}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa as ofertas agendadas e publica as que estão vencidas.';

    public function __construct(private readonly ScheduledOfferService $scheduledOffers)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $limit = $limit > 0 ? $limit : 50;

        $processed = $this->scheduledOffers->processDue($limit);

        $this->info(sprintf('Processados %d agendamentos.', $processed));

        return self::SUCCESS;
    }
}
