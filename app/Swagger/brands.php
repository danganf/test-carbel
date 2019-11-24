<?php
/**
 * @SWG\Get(
 *   path="/api/brands/{id}",
 *   summary="Get Brand by ID",
 *   operationId="carbel-get-brands-by-id",
 *   tags={"Brands"},
 *   @SWG\Parameter(in="header",name="x-auth-token",description="Auth Token",type="string",required=true,default=""),
 *   @SWG\Parameter(in="path", name="id", description="Brand ID", type="string", required=true ),
 *   @SWG\Response(response=200, description="successful operation")
 * )
 *
 * @SWG\Get(
 *   path="/api/brands",
 *   summary="Filter Brands",
 *   operationId="carbel-filter-brands",
 *   tags={"Brands"},
 *   @SWG\Parameter(in="header",name="x-auth-token",description="Auth Token",type="string",required=true,default=""),
 *   @SWG\Parameter(in="query",name="page",description="Filter by page",type="integer",default="1"),
 *   @SWG\Response(response=200, description="successful operation")
 * )
 *
 */
