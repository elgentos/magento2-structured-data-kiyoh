<?php

declare(strict_types=1);

namespace Elgentos\StructuredDataKiyoh\Plugin\ViewModel\Schema;

use Elgentos\Kiyoh\ViewModel\Kiyoh as KiyohViewModel;
use Elgentos\StructuredData\ViewModel\Schema\AbstractSchema;
use Elgentos\StructuredData\ViewModel\Schema\Website;

class Kiyoh
{
    private KiyohViewModel $kiyohViewModel;

    public function __construct(
        KiyohViewModel $kiyohViewModel
    ) {
        $this->kiyohViewModel = $kiyohViewModel;
    }

    public function afterGetStructuredData(
        Website $subject,
        array $result
    ): array {
        if ($this->kiyohViewModel->isEnabled()) {
            $result['aggregateRating'] = [
                '@type' => AbstractSchema::SCHEMA_TYPE_AGGREGATE_RATING,
                'ratingValue' => $this->kiyohViewModel->getRating(),
                'reviewCount' => $this->kiyohViewModel->getReviewCount()
            ];
        }

        return $result;
    }
}
