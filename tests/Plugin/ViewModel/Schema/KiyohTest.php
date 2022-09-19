<?php

/**
 * Copyright Elgentos. All rights reserved.
 * https://elgentos.nl
 */

declare(strict_types=1);

namespace Elgentos\StructuredDataKiyoh\Tests\Plugin\ViewModel\Schema;

use Elgentos\Kiyoh\ViewModel\Kiyoh as KiyohViewModel;
use Elgentos\StructuredData\ViewModel\Schema\Website;
use PHPUnit\Framework\TestCase;
use Elgentos\StructuredDataKiyoh\Plugin\ViewModel\Schema\Kiyoh;

/**
 * @coversDefaultClass \Elgentos\StructuredDataKiyoh\Plugin\ViewModel\Schema\Kiyoh
 */
class KiyohTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::afterGetStructuredData
     *
     * @dataProvider setDataProvider
     */
    public function testAfterGetStructuredData(
        bool $isEnabled,
        float $rating,
        int $reviewCount
    ): void {
        $subject = new Kiyoh(
            $this->createKiyohViewModelMock($isEnabled, $rating, $reviewCount)
        );

        $result = $subject->afterGetStructuredData(
            $this->createMock(Website::class),
            []
        );

        $this->assertIsArray($result);

        if ($isEnabled) {
            $this->assertArrayHasKey('aggregateRating', $result);
            $this->assertEquals($rating, $result['aggregateRating']['ratingValue']);
            $this->assertEquals($reviewCount, $result['aggregateRating']['reviewCount']);
        } else {
            $this->assertArrayNotHasKey('aggregateRating', $result);
        }
    }

    private function createKiyohViewModelMock(
        bool $isEnabled,
        float $rating,
        int $reviewCount
    ): KiyohViewModel {
        $viewModel = $this->createMock(KiyohViewModel::class);
        $viewModel->expects(self::once())
            ->method('isEnabled')
            ->willReturn($isEnabled);

        $viewModel->expects($isEnabled ? self::once() : self::never())
            ->method('getRating')
            ->willReturn($rating);

        $viewModel->expects($isEnabled ? self::once() : self::never())
            ->method('getReviewCount')
            ->willReturn($reviewCount);

        return $viewModel;
    }

    public function setDataProvider(): array
    {
        return [
            'disabled' => [false, 0, 0],
            'enabled' => [true, 5.6, 4]
        ];
    }
}
