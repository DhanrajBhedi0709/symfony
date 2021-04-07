<?php

namespace App\Tests\Controller\Admin;

use App\Controller\Admin\BlogController;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BlogControllerTest
 * @package App\Tests\Controller\Admin
 */
class BlogControllerTest extends WebTestCase
{
    /**
     * @return \string[][]
     */
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
     * testAdminUserLogin method is used for testing the login of admin user.
     *
     * @param string $method
     * @param string $url
     *
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

    /**
     * testIndex method is used to test the index method of admin/BlogController that showing correct no. of blog and pagination.
     */
    public function testIndex(): void
    {
        $client = static::createClient();
        $testUser = static::$container->get(UserRepository::class)->findOneByEmail('dhanraj0709@gmail.com');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/admin/');

        $this->assertResponseIsSuccessful();

        $this->assertGreaterThan(0, $crawler->filter('table > tbody > tr')->count());

        $this->assertEquals(3, $crawler->filter('table > tbody > tr')->count());

        $this->assertLessThan($client->getKernel()->getContainer()->getParameter('page_size')+1, $crawler->filter('table > tr')->count());

    }

    /**
     * testNew method is used for testing whether user is able to publish blog or not.
     */
    public function testNew(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'dhanraj0709@gmail.com',
            'PHP_AUTH_PW' => 'Abcd@1234',
        ]);
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

    /**
     * testshow method is used for testing a show method that showing correct and entire blog.
     */
    public function testShow()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'dhanraj0709@gmail.com',
            'PHP_AUTH_PW' => 'Abcd@1234',
        ]);

        $client->request('GET', '/admin/blog/three-things-in-life-that-aren-t-worth-the-effort');

        $this->assertResponseIsSuccessful();

    }

    /**
     * testEdit method is used for testing whether user is able to edit blog or not.
     */
    public function testEdit()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'dhanraj0709@gmail.com',
            'PHP_AUTH_PW' => 'Abcd@1234',
        ]);

        $crawler = $client->request('GET', '/admin/1/edit');
        $form = $crawler->selectButton('Update')->form();
        $form['post[title]'] = 'Three Things in Life That Are Worth The Effort';
        $form['post[summary]'] = 'To be more efficient and happy, cut the waste and damaging activities from your life';
        $form['post[content]'] = 'If you’re reading this, there are a million-and-a-half things you want to do: Build your business, exercise more, spend more time with friends and family, read more books, etc.';
        $form['post[tags]'] = 'news';
        $form['post[thumbnail]']->upload('C:\Users\dhanraj.bhedi\Downloads\1_JgvK8XYmhPr8Hz3thMOx2A.jpeg');
        $client->submit($form);

        $testBlog = static::$container->get(PostRepository::class)->findOneBy(['id' => 1]);

        $this->assertSame('Three Things in Life That Are Worth The Effort', $testBlog->getTitle());

    }

    /**
     * testDelete method is used for testing whether user is able to delete a blog or not.
     */
    public function testDelete()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'dhanraj0709@gmail.com',
            'PHP_AUTH_PW' => 'Abcd@1234',
        ]);

        $crawler = $client->request('GET', '/admin/1/edit');

        $client->submit($crawler->selectButton('Delete')->form());

        $this->assertResponseRedirects('/admin/', Response::HTTP_FOUND);

        $testPost = static::$container->get(PostRepository::class)->find(1);

        $this->assertNull($testPost);

    }

    /**
     * testMyCommentShow method is used for testing myCommentShow method whether it is showing only logged in user comment.
     */
    public function testMyCommentShow()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'dhanraj0709@gmail.com',
            'PHP_AUTH_PW' => 'Abcd@1234',
        ]);

        $crawler = $client->request('GET', '/admin/comment');

        $this->assertResponseIsSuccessful();

        $this->assertGreaterThan(0, $crawler->filter('table > tbody > tr')->count());

        $this->assertLessThan($client->getKernel()->getContainer()->getParameter('page_size')+1, $crawler->filter('table > tr')->count());
    }

}
