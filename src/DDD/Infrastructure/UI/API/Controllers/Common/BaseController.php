<?php

declare(strict_types=1);

namespace DDD\Infrastructure\UI\API\Controllers\Common;

use App\Http\Controllers\Controller;
use DDD\Infrastructure\UI\Helpers\CommonJSONResponse;

class BaseController extends Controller
{
    use CommonJSONResponse;
}
