<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\ClassInformation;
use App\Domain\Entities\Group;
use App\Domain\Entities\Server;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Common\Utility;
use Illuminate\Support\Facades\DB;

class ClassInformationRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = ClassInformation::class;
    /**
     * @var ClassInformation
     */
    private $classInformation;

    /**
     * ClassInformation constructor.
     *
     * @param ClassInformation $classInformation
     */
    public function __construct(ClassInformation $classInformation)
    {
        $this->classInformation = $classInformation;
    }
}
