<?php

namespace App\Console\Commands;

use App\Application\Offers\Services\OfferService;
use Illuminate\Console\Command;

class GenerateDiscountedOfferCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:replicate-last';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replicate the most recent offer with a 10% discount applied to the price.';

    public function __construct(
        private readonly OfferService $offers,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $offer = $this->offers->cloneLatestWithDiscount(10);

        if ($offer === null) {
            $this->warn('No offers available to replicate.');

            return self::SUCCESS;
        }

        $this->info(sprintf(
            'New offer #%d created with discounted price %0.2f %s.',
            $offer->id,
            $offer->price,
            $offer->currency,
        ));

        return self::SUCCESS;
    }
}
