<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class BlogControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertGreaterThan(0, $crawler->filter('div.post')->count());

        $this->assertLessThan($client->getKernel()->getContainer()->getParameter('page_size')+1, $crawler->filter('div.post')->count());

    }

    public function testAddComment(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('dhanraj0709@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/');
        $postLink = $crawler->filter('div.post')->first()->filter('a')->eq(2)->link();
        $crawler = $client->request('GET', $postLink->getUri());

        $form = $crawler->selectButton('Comment')->form();
        $form['comment[content]'] = 'Hi, Symfony!';
        $client->submit($form);
        $crawler = $client->followRedirect();
        $newComment = $crawler->filter('.post-comments')->first()->filter('.media-body > p')->text();

        $this->assertSame('Hi, Symfony!', $newComment);
    }


}
