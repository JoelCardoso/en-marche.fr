<?php

namespace Tests\AppBundle\Command;

use AppBundle\DataFixtures\ORM\LoadAdherentData;
use AppBundle\DataFixtures\ORM\LoadArticleData;
use AppBundle\DataFixtures\ORM\LoadEventData;
use AppBundle\DataFixtures\ORM\LoadProposalData;
use AppBundle\DataFixtures\ORM\LoadTimelineData;
use Tests\AppBundle\SqliteWebTestCase;

/**
 * @group functional
 */
class AlgoliaSynchronizeCommandTest extends SqliteWebTestCase
{
    /**
     * @dataProvider dataProviderTestCommand
     */
    public function testCommand(array $parameters, array $expectedOutputs)
    {
        $output = $this->runCommand('app:algolia:synchronize', $parameters);

        foreach ($expectedOutputs as $expectedOutput) {
            $this->assertContains($expectedOutput, $output);
        }
    }

    public function dataProviderTestCommand(): array
    {
        return [
            [
                ['entityName' => 'AppBundle:Event'],
                ['Synchronizing entity AppBundle\Entity\Event ... done, 15 records indexed'],
            ],
            [
                ['entityName' => 'AppBundle\Entity\Timeline\Theme'],
                ['Synchronizing entity AppBundle\Entity\Timeline\Theme ... done, 5 records indexed'],
            ],
            [
                [], // no parameters
                [
                    'Synchronizing entity AppBundle\Entity\Article ... done, 180 records indexed',
                    'Synchronizing entity AppBundle\Entity\Proposal ... done, 3 records indexed',
                    'Synchronizing entity AppBundle\Entity\Clarification ... done, 0 records indexed',
                    'Synchronizing entity AppBundle\Entity\CustomSearchResult ... done, 0 records indexed',
                    'Synchronizing entity AppBundle\Entity\Event ... done, 15 records indexed',
                    'Synchronizing entity AppBundle\Entity\Timeline\Profile ... done, 5 records indexed',
                    'Synchronizing entity AppBundle\Entity\Timeline\Theme ... done, 5 records indexed',
                    'Synchronizing entity AppBundle\Entity\Timeline\Measure ... done, 17 records indexed',
                ],
            ],
        ];
    }

    public function setUp()
    {
        $this->loadFixtures([
            LoadAdherentData::class,
            LoadArticleData::class,
            LoadEventData::class,
            LoadProposalData::class,
            LoadTimelineData::class,
        ]);

        parent::setUp();
    }

    public function tearDown()
    {
        $this->loadFixtures([]);

        parent::tearDown();
    }
}
