<?php

    namespace App\Tests\Controller;

    use App\Controller\RegisterController;
    use App\Repository\UserRepository;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class RegisterControllerTest extends WebTestCase
    {
        public function testRegister(): void
        {
            $client = static::createClient();

            $crawler = $client->request('GET', '/register');

            $form = $crawler->selectButton('Register')->form();
            $form['registration_form[name]'] = 'Dhrumil Savsani';
            $form['registration_form[email]'] = 'dhrumil@gmail.com';
            $form['registration_form[password][first]'] = 'Abcd@1234';
            $form['registration_form[password][second]'] = 'Abcd@1234';
            $form['registration_form[profileImage]']->upload('C:\Users\dhanraj.bhedi\Downloads\avatar-3.jpg');
            $client->submit($form);
            $crawler = $client->followRedirect();
            $newUser = $crawler->filter('ul.nav-menu > li')->last()->filter('a')->text();

            $this->assertSame('Dhrumil Savsani(Logout)', $newUser);

            $testUser = static::$container->get(UserRepository::class)->findOneByEmail('dhrumil@gmail.com');
            $this->assertNotNull($testUser);
        }

    }
