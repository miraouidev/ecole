<?php

namespace App\Controller;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsController]
class MediaController extends AbstractController
{
    
    #[Route('/api/media/upload', name: 'image_upload', methods: ['POST'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): JsonResponse {
        $uploadedFile = $request->files->get('file');
        $name    = $request->request->get('name');
        $info    = $request->request->get('info');
        $dossier = $request->request->get('dossier');

        if (!$uploadedFile) {
            throw $this->createNotFoundException('No file provided (expected "file" field).');
        }
        if (!$dossier) {
            throw $this->createNotFoundException('Field "dossier" is required.');
        }

        // Base uploads dir defined in services.yaml (parameter "media_upload_dir")
        $baseUploadDir = $this->getParameter('media_upload_dir');

        $targetDir = rtrim($baseUploadDir, '/').'/'.trim($dossier, '/');
        $fs = new Filesystem();
        if (!$fs->exists($targetDir)) {
            $fs->mkdir($targetDir, 0775);
        }

        // Safe unique filename
        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $slugger->slug($originalName)->lower();
        $ext = $uploadedFile->guessExtension() ?: $uploadedFile->getClientOriginalExtension() ?: 'bin';
        $newFilename = $safeName.'-'.bin2hex(random_bytes(6)).'.'.$ext;

        try {
            $uploadedFile->move($targetDir, $newFilename);
        } catch (FileException $e) {
            throw $this->createAccessDeniedException('Could not move uploaded file: '.$e->getMessage());
        }

        // Create and persist Media
        $media = new Media();
        $media->setName($name);
        $media->setInfo($info);
        $media->setDossier($dossier);

        // Build a link users can access; if served under /public, expose relative path under /uploads/...
        // Example assumes $baseUploadDir = %kernel.project_dir%/public/uploads
        $publicPath = '/uploads/'.trim($dossier, '/').'/'.$newFilename;
        $media->setLink($publicPath);

        $em->persist($media);
        $em->flush();

        return new JsonResponse([
            'status'  => 'success',
            'message' => 'File uploaded successfully',
            'media'   => [
                'id'      => $media->getId(),
                'name'    => $media->getName(),
                'link'    => $media->getLink(),
                'dossier' => $media->getDossier(),
            ]
        ], JsonResponse::HTTP_CREATED);
    }
}
