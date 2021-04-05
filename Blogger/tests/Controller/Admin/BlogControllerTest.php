<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\BlogController;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use phpDocumentor\Reflection\Types\Self_;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BlogControllerTest extends WebTestCase
{
    public function adminUrls(): array
    {
        return [
            ['GET', '/admin/'],
            ['GET', '/admin/new'],
            ['GET', '/admin/blog/three-things-in-life-that-aren-t-worth-the-effort'],
            ['GET', '/admin/1/edit'],
            ['POST', '/admin/1'],
            ['GET', '/admin/comment']
        ];
    }

    /**
     * @dataProvider adminUrls
     */
    public function testAdminUserLogin(string $method, string $url)
    {
        $client = static::createClient();

        $client->request($method, $url);
        $client->followRedirects();

        $this->assertResponseRedirects(
            '/login',
            Response::HTTP_FOUND
        );
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $testUser = static::$container->get(UserRepository::class)->findOneByEmail('dhanraj0709@gmail.com');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/admin/');

        $this->assertResponseIsSuccessful();

        $this->assertGreaterThan(0, $crawler->filter('table > tbody > tr')->count());

        $this->assertLessThan($client->getKernel()->getContainer()->getParameter('page_size')+1, $crawler->filter('table > tr')->count());

    }

    public function testNew(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('dhanraj0709@gmail.com');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/admin/new');
        $form = $crawler->selectButton('Save')->form();
        $form['post[title]'] = '7 Habits I Avoid to Become Highly Productive';
        $form['post[summary]'] = 'Cutting these things helped me boost my productivity.';
        $form['post[content]'] = 'If you’re reading this, there are a million-and-a-half things you want to do: Build your business, exercise more, spend more time with friends and family, read more books, etc.';
        $form['post[tags]'] = 'news';
        $form['post[thumbnail]']->upload('C:\Users\dhanraj.bhedi\Downloads\1_JgvK8XYmhPr8Hz3thMOx2A.jpeg');
        $client->submit($form);
        $crawler = $client->request('GET','/admin/');
        $newTitle = $crawler->filter('table > tbody > tr')->first()->filter('td')->text();

        $this->assertSame('7 Habits I Avoid to Become Highly Productive', $newTitle);
    }


}
