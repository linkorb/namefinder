<?php

namespace NameFinder\Controller;

use phpWhois\Whois;

class AppController
{
    public function indexController($app, $request)
    {
        $generators = $app->nameRepository->getGenerators();
        //print_r($generators);
        $checkers = $app->nameCheckRepository->getCheckers();
        //print_r($checkers);
        $data = [
            'generators' => $generators,
            'checkers' => $checkers
        ];

        return $app->renderResponse('index.html.twig', $data);
    }
    
    public function searchController($app, $request)
    {
        $generators = $app->nameRepository->getGenerators();
        //print_r($generators);
        $checkers = $app->nameCheckRepository->getCheckers();
        //print_r($checkers);
        $g = [];
        $c = [];
        
        foreach ($generators as $generator) {
            if ($request->request->has($generator)) {
                //echo "GEN " . $generator . "<br />\n";
                $g[] = $generator;
            }
        }
        foreach ($checkers as $checker) {
            if ($request->request->has($checker)) {
                //echo "CHECK " . $checker . "<br />\n";
                $c[] = $checker;
            }
        }
        $names = $app->nameRepository->search($g, $c, 20);
    
        $data = ['names'=>$names];
        return $app->renderResponse('search.html.twig', $data);
    }
    
    public function viewNameController($app, $request, $name)
    {
        $data = [
            'name' => $name
        ];

        $whois = new Whois();
        $whois->deepWhois = false;
        
        $data['rating'] = $app->nameRepository->getRatingByName($name);
        $data['statuses'] = $app->nameRepository->getStatusByName($name);

        
        $data['whois'] = implode("\n", $whois->lookup($name . '.com', false)['rawdata']);
        
        return $app->renderResponse('view_name.html.twig', $data);
    }

    public function rateNameController($app, $request, $name, $rating)
    {
        $app->nameRepository->setRatingByName($name, $rating);
        return $app->redirect('/names/' . $name);
    }
    
    public function ratingController($app, $request, $rating)
    {
        $data = [
            'rating' => $rating
        ];

        $data['names'] = $app->nameRepository->getByRating($rating);

        return $app->renderResponse('rating.html.twig', $data);
    }
}
