<?php

    namespace App\Service;

    use Symfony\Component\HttpFoundation\File\Exception\FileException;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\String\Slugger\SluggerInterface;
    use Psr\Log\LoggerInterface;

    /**
     * Class FileUploader
     * @package App\Service
     */
class FileUploader
{
    /**
     * @var SluggerInterface
     */
    private $slugger;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * FileUploader constructor.
     * @param SluggerInterface $slugger
     * @param LoggerInterface $logger
     */
    public function __construct(SluggerInterface $slugger, LoggerInterface $logger)
    {
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    /**
     * upload method is used to upload file from service.
     *
     * @param UploadedFile $file
     * @param string $location
     * @return string
     */
    public function upload(UploadedFile $file, string $location = 'thumbnail')
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($location, $fileName);
        } catch (FileException $e) {
            $this->logger->error("Logger => " . $e->getMessage() . " code => " . $e->getCode());
            throw $e;
        }

        return $location . '/' . $fileName;
    }

    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
