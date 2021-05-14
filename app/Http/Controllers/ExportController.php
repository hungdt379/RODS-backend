<?php

namespace App\Http\Controllers;

use App\Common\Utility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator as url;
use Illuminate\Http\RedirectResponse as Redirect;
use Illuminate\Support\Facades\Input;
use App\Helpers\GenerateHelper;
use App\Http\Controllers\BaseController as BaseController;
use App\Domain\Services\ExportService;

class ExportController extends BaseController {

    /**
     * @var ExportService
     */
    private $exportService;

    /**
     * ExportService constructor.
     *
     * @param ExportService $exportService
     */
    public function __construct(ExportService $exportService) {
        $this->exportService = $exportService;
    }

    function serverStatistics(Request $request) {
        return $this->exportService->exportServerStatistics($request->all());
    }

}
