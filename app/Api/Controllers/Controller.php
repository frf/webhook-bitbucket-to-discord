<?php

namespace App\Api\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 * @OA\Info(
 *    title="Select Application",
 *    version="1.0.0",
 *    contact="your@email.com"
 * )
 * @OA\Server(
 *      url=L5_SWAGGER_API_HOST,
 *      description="Api Select Develop"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
