<?php

    namespace App\Tests\Repository;

    use App\Entity\Post;
    use phpDocumentor\Reflection\Type;
    use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

    class ProductRepositoryTest extends KernelTestCase
    {
        /**
         * @var \Doctrine\ORM\EntityManager
         */
        private $entityManager;

        protected function setUp(): void
        {
            $kernel = self::bootKernel();

            $this->entityManager = $kernel->getContainer()
                ->get('doctrine')
                ->getManager();
        }

        public function tagProvider()
        {
            return [
                ['news', 2],
                ['travel', 1],
                ['technology', 2]
            ];
        }

        /**
         * @param string $tag
         * @param int $res
         *
         * @dataProvider tagProvider
         */
        public function testFindByCategory(string $tag, int $res)
        {
            $posts = $this->entityManager
                ->getRepository(Post::class)
                ->findByCategory($tag)->getResult();

            $this->assertEquals($res, count($posts));

            foreach ($posts as $post){
                $this->assertStringContainsString($tag, $post->getTags());
            }
        }

        public function publishDateProvider()
        {
            return [
                [1, 2021, 1],
                [3, 2021, 4],
            ];
        }

        /**
         * @param int $month
         * @param int $year
         * @param int $res
         *
         * @dataProvider publishDateProvider
         */
        public function testFindByPublishDate(int $month, int $year, int $res)
        {
            $posts = $this->entityManager
                ->getRepository(Post::class)
                ->findByPublishDate($month, $year)->getResult();

            $this->assertEquals($res, count($posts));
        }

        public function testFindByDistinctDate()
        {
            $posts = $this->entityManager
                ->getRepository(Post::class)
                ->findByDistinctDate();

            $this->assertEquals(2, count($posts));
        }

        protected function tearDown(): void
        {
            parent::tearDown();
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }