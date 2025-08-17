<?php

namespace App\Controller;

use App\Entity\Langue;
use App\Repository\SiteHeaderRepository;
use App\Repository\SiteReseauRepository;
use App\Repository\SiteTopImageRepository;
use App\Repository\SiteAboutUsRepository;
use App\Repository\SiteAboutTicketRepository;
use App\Repository\SiteWeWhatRepository;
use App\Repository\SiteWeWhatTicketRepository;
use App\Repository\SiteOurProgramRepository;
use App\Repository\SiteEventRepository;
use App\Repository\SiteEventTicketRepository;
use App\Repository\SiteOurTeamsRepository;
use App\Repository\SiteFooterRepository;
use App\Repository\LangueRepository;
use App\Repository\SitePageGeneriqueRepository;
use Psr\Cache\CacheItemInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class WebsiteController extends AbstractController
{
    public function __construct(
        private CacheInterface $cache,
        private LangueRepository $langueRepo,
        private SiteHeaderRepository $siteHeaderRepo,
        private SiteReseauRepository $siteReseauRepo,
        private SiteTopImageRepository $siteTopImageRepo,
        private SiteAboutUsRepository $siteAboutUsRepo,
        private SiteAboutTicketRepository $siteAboutTicketRepo,
        private SiteWeWhatRepository $siteWeWhatRepo,
        private SiteWeWhatTicketRepository $siteWeWhatTicketRepo,
        private SiteOurProgramRepository $siteOurProgramRepo,
        private SiteEventRepository $siteEventRepo,
        private SiteEventTicketRepository $siteEventTicketRepo,
        private SiteOurTeamsRepository $siteOurTeamsRepo,
        private SiteFooterRepository $siteFooterRepo,
    ) {}

    #[Route('/website/{code}', name: 'website_by_langue', methods: ['GET'])]
    public function getWebHome(string $code): JsonResponse
    {
        $code = strtolower($code);

        // Cache key per language
        $cacheKey = sprintf('website_payload_%s', $code);

        $payload = $this->cache->get($cacheKey, function (CacheItemInterface $item) use ($code) {

            // Cache TTL (adjust as you like)
            $item->expiresAfter(600); // 10 minutes

            // 1) Resolve language
            /** @var Langue|null $langue */
            $langue = $this->langueRepo->findOneBy(['code' => $code]);
            if (!$langue) {
                throw new NotFoundHttpException(sprintf('Langue "%s" not found.', $code));
            }

            // Helper to filter by langue + isActive when applicable
            $byLangActive = static fn($qbAlias) => function ($qb) use ($qbAlias, $code) {
                $qb->join("$qbAlias.langue", 'l')
                   ->andWhere('l.code = :code')->setParameter('code', $code);
                // If the entity has isActive (most of your "site" entities do)
                // you can keep a convention: add "AND alias.isActive = true"
                $em = $qb->getEntityManager();
                $meta = $em->getClassMetadata($qb->getRootEntities()[0]);
                if ($meta->hasField('isActive')) {
                    $qb->andWhere("$qbAlias.isActive = true");
                }
            };

            $headers = $this->siteHeaderRepo->createQueryBuilder('h')
            ->join('h.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('h.isActive = true') // only if entity has isActive
            ->getQuery()
            ->getResult();

        $reseaux = $this->siteReseauRepo->createQueryBuilder('r')
            ->join('r.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('r.isActive = true')
            ->getQuery()
            ->getResult();

        $topImages = $this->siteTopImageRepo->createQueryBuilder('ti')
            ->join('ti.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('ti.isActive = true')
            ->getQuery()
            ->getResult();

        $aboutUs = $this->siteAboutUsRepo->createQueryBuilder('a')
            ->join('a.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('a.isActive = true')
            ->getQuery()
            ->getResult();

        $aboutTickets = $this->siteAboutTicketRepo->createQueryBuilder('at')
            ->join('at.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('at.isActive = true')
            ->getQuery()
            ->getResult();

        $weWhat = $this->siteWeWhatRepo->createQueryBuilder('w')
            ->join('w.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('w.isActive = true')
            ->getQuery()
            ->getResult();

        $weWhatTickets = $this->siteWeWhatTicketRepo->createQueryBuilder('wt')
            ->join('wt.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('wt.isActive = true')
            ->getQuery()
            ->getResult();

        $ourPrograms = $this->siteOurProgramRepo->createQueryBuilder('op')
            ->join('op.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('op.isActive = true')
            ->getQuery()
            ->getResult();

        $events = $this->siteEventRepo->createQueryBuilder('e')
            ->join('e.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('e.isActive = true')
            ->getQuery()
            ->getResult();

        $eventTickets = $this->siteEventTicketRepo->createQueryBuilder('et')
            ->join('et.langue', 'l')
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->andWhere('et.isActive = true')
            ->getQuery()
            ->getResult();

            // 3) Build one response object
            return [
                'langue'         => $code,
                'header'         => $headers,
                'reseaux'        => $reseaux,
                'topImages'      => $topImages,
                'aboutUs'        => $aboutUs,
                'aboutTickets'   => $aboutTickets,
                'weWhat'         => $weWhat,
                'weWhatTickets'  => $weWhatTickets,
                'ourPrograms'    => $ourPrograms,
                'events'         => $events,
                'eventTickets'   => $eventTickets,
                //'ourTeams'       => $ourTeams,
                //'footer'         => $footers,
            ];
        });

        // Use your existing serializer groups so fields match your API resources
        return $this->json(
            $payload,
            200,
            [],
            [
                'groups' => [
                    'siteheader:read',
                    'sitereseau:read',
                    'sitetopimage:read',
                    'siteaboutus:read',
                    'siteaboutticket:read',
                    'sitewewhat:read',
                    'sitewewhatticket:read',
                    'siteourprogram:read',
                    'siteevent:read',
                    'siteeventticket:read',
                    'siteourteams:read',
                    'sitefooter:read',
                ],
                'skip_null_values' => false
            ]
        );
    }

    #[Route('/website/page-generique', name: 'website_by_langue', methods: ['POST'])]
    public function getWebPage(Request $request,SitePageGeneriqueRepository $repo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['url'])) {
            return $this->json(
                ['error' => 'Missing "url" parameter in body'],
                400
            );
        }

        $url = trim($data['url']);

        // Recherche page active avec cette URL
        $page = $repo->findOneBy([
            'url' => $url,
            'isActive' => true
        ]);

        if (!$page) {
            throw new NotFoundHttpException("Page not found for url: $url");
        }

        return $this->json(
            $page,
            200,
            [],
            [
                'groups' => ['sitepage:read'],
                'skip_null_values' => false
            ]
        );
    
    }
}
