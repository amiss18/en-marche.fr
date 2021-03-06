<?php

namespace Tests\AppBundle\Controller\Admin;

use AppBundle\DataFixtures\ORM\LoadAdherentData;
use AppBundle\DataFixtures\ORM\LoadAdminData;
use AppBundle\Mailer\Message\CommitteeApprovalConfirmationMessage;
use AppBundle\Mailer\Message\CommitteeApprovalReferentMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\Controller\ControllerTestTrait;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * @group functional
 * @group admin
 */
class AdminCommitteeControllerTest extends WebTestCase
{
    use ControllerTestTrait;

    private $committeeRepository;

    public function testApproveAction(): void
    {
        $committee = $this->committeeRepository->findOneByUuid(LoadAdherentData::COMMITTEE_2_UUID);

        $this->assertFalse($committee->isApproved());

        $this->authenticateAsAdmin($this->client);

        $this->client->request(Request::METHOD_GET, sprintf('/admin/committee/%s/approve', $committee->getId()));
        $this->assertResponseStatusCode(Response::HTTP_FOUND, $this->client->getResponse());

        $this->get('doctrine.orm.entity_manager')->clear();

        $committee = $this->committeeRepository->findOneByUuid(LoadAdherentData::COMMITTEE_2_UUID);

        $this->assertTrue($committee->isApproved());
        $this->assertCount(1, $this->getEmailRepository()->findRecipientMessages(CommitteeApprovalConfirmationMessage::class, 'benjyd@aol.com'));
        $this->assertCount(1, $this->getEmailRepository()->findRecipientMessages(CommitteeApprovalReferentMessage::class, 'referent@en-marche-dev.fr'));
    }

    protected function setUp()
    {
        parent::setUp();

        $this->init([
            LoadAdminData::class,
            LoadAdherentData::class,
        ]);

        $this->committeeRepository = $this->getCommitteeRepository();
    }

    protected function tearDown()
    {
        $this->kill();

        $this->committeeRepository = null;

        parent::tearDown();
    }
}
