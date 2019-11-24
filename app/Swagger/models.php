<?php
/**
 * @SWG\Get(
 *   path="/api/models/{id}",
 *   summary="Get Model by ID",
 *   operationId="carbel-get-model-by-id",
 *   tags={"Models"},
 *   @SWG\Parameter(in="header",name="x-auth-token",description="Auth Token",type="string",required=true,default=""),
 *   @SWG\Parameter(in="path", name="id", description="Model ID", type="string", required=true ),
 *   @SWG\Response(response=200, description="successful operation")
 * )
 *
 * @SWG\Get(
 *   path="/api/models",
 *   summary="Filter Models",
 *   operationId="carbel-filter-models",
 *   tags={"Models"},
 *   @SWG\Parameter(in="header",name="x-auth-token",description="Auth Token",type="string",required=true,default=""),
 *   @SWG\Parameter(in="query",name="page",description="Filter by page",type="integer",default="1"),
 *   @SWG\Parameter(in="query",name="brand_id",description="Filter by brand_id",type="integer",default=""),
 *   @SWG\Response(response=200, description="successful operation")
 * )
 *
 */
