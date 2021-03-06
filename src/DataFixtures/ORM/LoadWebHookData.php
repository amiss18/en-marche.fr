<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\WebHook\WebHook;
use AppBundle\WebHook\Event;
use AppBundle\WebHook\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadWebHookData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $webHook1 = new WebHook(
            $this->getReference('web_hook_client_1'),
            Event::USER_DELETION(),
            [
                'http://test.com/awesome',
                'https://www.en-marche.fr/webhook/endpoint',
            ]
        );
        $manager->persist($webHook1);

        $webHook2 = new WebHook(
            $this->getReference('web_hook_client_1'),
            Event::USER_MODIFICATION(),
            [
                'http://test.com/awesome',
                'https://www.en-marche.fr/webhook/endpoint',
            ]
        );
        $manager->persist($webHook2);

        $webHook3 = new WebHook(
            $this->getReference('web_hook_client_2'),
            Event::USER_MODIFICATION(),
            [
                'http://client5.com/web_hook',
            ]
        );
        $manager->persist($webHook3);

        $webHook4 = new WebHook(
            $this->getReference('web_hook_client_api'),
            Event::USER_UPDATE_SUBSCRIPTIONS(),
            [
                'https://api.mailchimp.com/lists',
            ],
            Service::MAILCHIMP()
        );
        $manager->persist($webHook4);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadClientData::class];
    }
}
