<?php

use App\Hydrators\OrganisationsCollection\JsonOrganisationsCollectionHydrator;
use App\Hydrators\RelationsCollection\ArrayRelationsCollectionHydrator;
use App\Repositories\Organisations\OrganisationsRepositoryInterface;
use Illuminate\Http\Request;

$app->group(['prefix' => '/api/v1/organisations'], function () use ($app) {

    /** @var OrganisationsRepositoryInterface $repository */
    $repository = $app->make(OrganisationsRepositoryInterface::class);

    $app->post('/', function (Request $request) use ($app, $repository) {
        /** @var JsonOrganisationsCollectionHydrator $hydrator */
        $hydrator = $app->make(JsonOrganisationsCollectionHydrator::class);
        $repository->store($hydrator->hydrate($request->getContent()));
        
        return new \Illuminate\Http\Response(null, 201);
    });

    $app->get('{title}/relations', function (Request $request, $title) use ($app, $repository) {
        $title = urldecode($title);
        $page = max(1, (int)$request->get('page', 1));

        $relations = $repository->getRelationsByTitle($title, $page);
        /** @var ArrayRelationsCollectionHydrator $hydrator */
        $hydrator = $app->make(ArrayRelationsCollectionHydrator::class);

        return $hydrator->extract($relations);
    });

    $app->delete('/', function () use ($repository) {
        $repository->deleteAll();

        return new \Illuminate\Http\Response(null, 204);
    });
});
