<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN', '', 'You must be admin to access this page')]
    #[Route('/chart/recipe_per_year/{year?}/{type?}', name: 'app_chart_recipe_per_year')]
    public function index(
        ChartBuilderInterface $chartBuilder,
        RecipeRepository $repository,
        ManagerRegistry $doctrine,
        TranslatorInterface $translator,
        ?string $year = null,
        ?string $type = null,
    ): Response {
        /**
         * @var \Doctrine\ORM\EntityManager $em
         */
        $em = $doctrine->getManager();
        $emConfig = $em->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');

        // Get datas for chart
        $monthRecipes = [];
        $year = (null === $year) ? date('Y') : $year;
        /** @var array<int,array{gBmonth:int,gCount:int}> $recipesPerMonth */
        $recipesPerMonth = $repository->groupByMonth($year);
        for ($i = 1; $i <= 12; ++$i) {
            $found_key = array_search($i, array_column($recipesPerMonth, 'gBmonth'));
            $monthRecipes[] = (false === $found_key) ? 0 : $recipesPerMonth[$found_key]['gCount'];
        }

        // Building chart
        switch ($type) {
            case 'line':
                $type = Chart::TYPE_LINE;
                break;
            case 'bar':
                $type = Chart::TYPE_BAR;
                break;
            default:
                $type = Chart::TYPE_BAR;
        }

        $chart = $chartBuilder->createChart($type);
        $chart->setData([
            'labels' => [
                $translator->trans('app.january.label'),
                $translator->trans('app.february.label'),
                $translator->trans('app.march.label'),
                $translator->trans('app.april.label'),
                $translator->trans('app.may.label'),
                $translator->trans('app.june.label'),
                $translator->trans('app.july.label'),
                $translator->trans('app.august.label'),
                $translator->trans('app.september.label'),
                $translator->trans('app.october.label'),
                $translator->trans('app.november.label'),
                $translator->trans('app.december.label'),
            ],
            'datasets' => [
                [
                    'label' => "$year",
                    'backgroundColor' => '#3459e6',
                    'data' => $monthRecipes,
                ],
            ],
        ]);

        $chart->setOptions([
            'backgroundColor' => '#FFFFFF',
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => $translator->trans('recipe.recipe-per-month.label'),
                    'fullSize' => true,
                    'font' => [
                        'size' => 20,
                    ],
                ],
                'zoom' => [
                    'zoom' => [
                        'wheel' => ['enabled' => true],
                        'pinch' => ['enabled' => true],
                        'mode' => 'x',
                    ],
                ],
            ],
        ]);

        return $this->render('pages/chart/index.html.twig', [
            'chart' => $chart,
            'currentYear' => date('Y'),
            'chartYear' => $year,
            'currentType' => $type,
        ]);
    }
}
