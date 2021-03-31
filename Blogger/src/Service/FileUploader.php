<?php

    namespace App\Service;

    use Symfony\Component\HttpFoundation\File\Exception\FileException;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\String\Slugger\SluggerInterface;
    use Psr\Log\LoggerInterface;

    class FileUploader
    {
        private $slugger;

        public function __construct(SluggerInterface $slugger)
        {
            $this->slugger = $slugger;
        }

        public function upload(UploadedFile $file, LoggerInterface $logger)
        {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $this->slugger->slug($originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            try {
                $file->move('thumbnail', $fileName);
            } catch (FileException $e) {
                $logger->error("Logger => " .$e->getMessage() ." code => " . $e->getCode());
                new \Exception("File cannot be uploaded");
            }

            return 'thumbnail/'.$fileName;
        }

        public function getTargetDirectory()
        {
            return $this->targetDirectory;
        }
    }