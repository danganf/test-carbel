<?php
/**
 * @SWG\Get(
 *   path="/api/cars/detail/{sku}",
 *   summary="Get Cars by SKU",
 *   operationId="carbel-get-cars-by-sku",
 *   tags={"Cars"},
 *   @SWG\Parameter(in="header",name="x-auth-token",description="Auth Token",type="string",required=true,default=""),
 *   @SWG\Parameter(in="path", name="sku", description="Cars Sku", type="string", required=true ),
 *   @SWG\Response(response=200, description="successful operation")
 * )
 *
 * @SWG\Get(
 *   path="/api/cars/{type}/{brand}/{model}",
 *   summary="Filter Cars",
 *   operationId="carbel-filter-cars",
 *   tags={"Cars"},
 *   @SWG\Parameter(in="header",name="x-auth-token",description="Auth Token",type="string",required=true,default=""),
 *   @SWG\Parameter(in="path", name="type", description="Cars type name", type="string", default="Carro", required=true ),
 *   @SWG\Parameter(in="path", name="brand", description="Cars brand name", type="string", required=false ),
 *   @SWG\Parameter(in="path", name="model", description="Cars Model name", type="string", required=false ),
 *   @SWG\Parameter(in="query",name="page",description="Filter by page",type="integer",default="1"),
 *   @SWG\Parameter(in="query",name="total_register",description="Filter by total register",type="integer",default="10"),
 *   @SWG\Parameter(in="query",name="exchange_type",description="Filter by exchange_type",type="string",default="Manual"),
 *   @SWG\Parameter(in="query",name="year_of",description="Filter by year of",type="string",default="2014"),
 *   @SWG\Parameter(in="query",name="year_until",description="Filter by year until",type="string",default="2014"),
 *   @SWG\Response(response=200, description="successful operation")
 * )
 *
 */
