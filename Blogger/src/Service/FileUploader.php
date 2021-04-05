<?php

    namespace App\Service;

    use Symfony\Component\HttpFoundation\File\Exception\FileException;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\String\Slugger\SluggerInterface;
    use Psr\Log\LoggerInterface;

class FileUploader
{
    private $slugger;
    private $logger;

    public function __construct(SluggerInterface $slugger, LoggerInterface $logger)
    {
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move('thumbnail', $fileName);
        } catch (FileException $e) {
            $this->logger->error("Logger => " . $e->getMessage() . " code => " . $e->getCode());
            new \Exception("File cannot be uploaded");
        }

        return 'thumbnail/' . $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
