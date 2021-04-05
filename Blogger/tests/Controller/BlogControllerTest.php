<?php

namespace App\Tests\Controller;

use App\Controller\BlogController;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class BlogControllerTest extends WebTestCase
{

    private $client;
    private $controller;

    public function setup(): void
    {
        $this->client = static::createClient();
        $this->controller = new BlogController();
    }
    
    public function testIndex(): void
    {

        $crawler = $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertGreaterThan(0, $crawler->filter('div.post')->count());

        $this->assertLessThan($this->client->getKernel()->getContainer()->getParameter('page_size')+1, $crawler->filter('div.post')->count());

    }

    public function testShow(){

        $postRepository = static::$container->get(PostRepository::class);

        $testPost = $postRepository->find(1);

        $crawler = $this->client->request('GET', "/blog/" . $testPost->getSlug());

        $this->assertResponseIsSuccessful();
    }

    public function testAddComment(): void
    {
        $userRepository = static::$container->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail('dhanraj0709@gmail.com');

        $this->client->loginUser($testUser);

        $crawler = $this->client->request('GET', '/');
        $postLink = $crawler->filter('div.post')->first()->filter('a')->eq(2)->link();
        $crawler = $this->client->request('GET', $postLink->getUri());

        $form = $crawler->selectButton('Comment')->form();
        $form['comment[content]'] = 'Hi, Symfony!';
        $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $newComment = $crawler->filter('.post-comments')->first()->filter('.media-body > p')->text();

        $this->assertSame('Hi, Symfony!', $newComment);
    }

    public function publicUrls(): array
    {
        return [
            ['/'],
            ['/blog/the-7-emails-you-should-send-every-week-to-get-ahead-in-your-career'],
            ['/register'],
            ['/login'],
        ];
    }

    /**
     * @dataProvider publicUrls
     */
    public function testAnonymousUserLogin(string $url)
    {
        $this->client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

//    public function testRenderCommentForm()
//    {
//        $postRepository = static::$container->get(PostRepository::class);
//        $testPost = $postRepository->find(1);
//        $response = $this->controller->renderCommentForm($testPost);
//
//        var_dump($response);
//    }

}
