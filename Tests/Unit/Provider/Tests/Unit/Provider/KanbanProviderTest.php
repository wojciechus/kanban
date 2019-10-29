<?php

namespace Tests\Unit\Provider;

use App\Models\Milestone;
use App\Providers\IssueProvider;
use App\Providers\KanbanProvider;
use App\Services\FulfillingCalculator;
use Prophecy\Argument;

class KanbanProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldReturnArrayDifferences(): void
    {
        $issueProviderMock = $this->getIssueProviderMock();
        $fulfillingCalculatorMock = $this->getFulfilingCalculatorMock();

        $kanbanProvider = new KanbanProvider($issueProviderMock, $fulfillingCalculatorMock);


        $result = $kanbanProvider->getKanbanFeed($this->getMilestonesArrayMock());
        self::assertCount(1,$result );

//
//        self::assertCount(3, $result);
//        self::assertEquals(-0.05, $result['https://wp.pl']);
//        self::assertEquals(0.14, $result['https://onet.pl']);
//        self::assertEquals(0, $result['https://google.com']);
//
//        $durationMainWebsiteProviderMock
//            ->getProphecy()
//            ->getTime(Argument::type('array'))
//            ->shouldHaveBeenCalledTimes(1);
//        $website1Mock
//            ->getProphecy()
//            ->getUrl()
//            ->shouldHaveBeenCalledTimes(1);
//        $website1Mock
//            ->getProphecy()
//            ->getDuration()
//            ->shouldHaveBeenCalledTimes(1);
//        $website2Mock
//            ->getProphecy()
//            ->getUrl()
//            ->shouldHaveBeenCalledTimes(1);
//        $website2Mock
//            ->getProphecy()
//            ->getDuration()
//            ->shouldHaveBeenCalledTimes(1);
//        $website3Mock
//            ->getProphecy()
//            ->getUrl()
//            ->shouldHaveBeenCalledTimes(1);
//        $website3Mock
//            ->getProphecy()
//            ->getDuration()
//            ->shouldHaveBeenCalledTimes(1);
    }


    private function getIssueProviderMock(): IssueProvider
    {
        $prophecy = $this->prophesize(IssueProvider::class);

        $prophecy
            ->getIssued(Argument::cetera())
            ->willReturn([]);


        return $prophecy->reveal();
    }

    private function getFulfilingCalculatorMock(): FulfillingCalculator
    {
        $prophecy = $this->prophesize(FulfillingCalculator::class);

        $prophecy
            ->percent(Argument::cetera())
            ->willReturn(25);


        return $prophecy->reveal();
    }

    private function getMilestonesArrayMock(): array
    {
        return [$this->getMilestoneMock()];
    }

    private function getMilestoneMock()
    {
        $prophecy = $this->prophesize(Milestone::class);

        return $prophecy->reveal();
    }
}
