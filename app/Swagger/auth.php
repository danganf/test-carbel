<?php
/**
 * @SWG\Post(
 *   path="/api/auth",
 *   summary="Authentication in API",
 *   operationId="carbel-auth",
 *   tags={"Auth"},
 *   @SWG\Parameter(in="header",name="x-auth-login",description="user Login",type="string",required=true,default="carbel"),
 *   @SWG\Parameter(in="header",name="x-auth-password",description="user Password",type="string",required=true,default="carbel123#"),
 *   @SWG\Response(response=200, description="successful operation")
 * )
 */
