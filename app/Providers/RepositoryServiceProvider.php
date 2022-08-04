<?php

namespace App\Providers;

use App\Http\Controllers\Api\PeriodTimeController;
use App\Interfaces\AuthInterface;
use App\Interfaces\CustomFieldInterface;
use App\Interfaces\CustomParamInterface;
use App\Interfaces\CustomSettingInterface;
use Illuminate\Support\ServiceProvider;

use App\Interfaces\GradeInterface;
use App\Interfaces\JobPositionInterface;
use App\Interfaces\LocationInterface;
use App\Interfaces\NationalityInterface;
use App\Interfaces\CustomTypeInterface;
use App\Interfaces\DurationPerformanceInterface;
use App\Interfaces\EmploymentInterface;
use App\Interfaces\FrequencyPeriodInterface;

use App\Interfaces\FrequencyReviewInterface;
use App\Interfaces\HumanResourceInterface;
use App\Interfaces\KeyResultInterface;
use App\Interfaces\MeasureInterface;
use App\Interfaces\ObjectiveInterface;
use App\Interfaces\ObjectiveLevelInterface;
use App\Interfaces\OkrPotentialInterface;
use App\Interfaces\OKRInterface;
use App\Interfaces\OrganizationTeamInterface;
use App\Interfaces\PeriodTimeInterface;
use App\Interfaces\PermissionInterface;
use App\Interfaces\ProfileInterface;
use App\Interfaces\RoleInterface;
use App\Interfaces\UserCustomInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\ProfileSettingInterface;
use App\Interfaces\ProfileFieldSettingInterface;
use App\Interfaces\VisibilityInterface;
use App\Interfaces\PerformanceInterface;
use App\Interfaces\TaskInterface;
use App\Interfaces\ConversationInterface;
use App\Repositories\AuthRepository;
use App\Repositories\CustomFieldRepository;
use App\Repositories\CustomParamRepository;
use App\Repositories\CustomSettingRepository;
use App\Repositories\GradeRepository;
use App\Repositories\JobPositionRepository;
use App\Repositories\LocationRepository;
use App\Repositories\NationalityRepository;
use App\Repositories\CustomTypeRepository;
use App\Repositories\DurationPerformanceRepository;
use App\Repositories\EmploymentRepository;
use App\Repositories\FrequencyPeriodRepository;
use App\Repositories\FrequencyReviewRepository;
use App\Repositories\HumanResourceRepository;
use App\Repositories\KeyResultRepository;
use App\Repositories\MeasureRepository;
use App\Repositories\ObjectiveLevelRepository;
use App\Repositories\OkrPotentialRepository;
use App\Repositories\ObjectiveRepository;
use App\Repositories\OKRRepository;
use App\Repositories\OrganizationTeamRepository;
use App\Repositories\PeriodTimeRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\RoleRepository;
use App\Repositories\UserCustomRepository;
use App\Repositories\UserRepository;
use App\Repositories\ProfileSettingRepository;
use App\Repositories\ProfileFieldSettingRepository;
use App\Repositories\VisibilityRepository;
use App\Repositories\PerformanceRepository;
use App\Repositories\TaskRepository;
use App\Repositories\ConversationRepository;
class RepositoryServiceProvider extends ServiceProvider
{

    protected $providers = [
        AuthInterface::class => AuthRepository::class,
        RoleInterface::class => RoleRepository::class,
        PermissionInterface::class => PermissionRepository::class,
        GradeInterface::class => GradeRepository::class,
        NationalityInterface::class => NationalityRepository::class,
        LocationInterface::class => LocationRepository::class,
        JobPositionInterface::class => JobPositionRepository::class,
        CustomTypeInterface::class => CustomTypeRepository::class,
        CustomSettingInterface::class => CustomSettingRepository::class,
        CustomParamInterface::class => CustomParamRepository::class,
        CustomFieldInterface::class => CustomFieldRepository::class,
        EmploymentInterface::class => EmploymentRepository::class,
        ProfileInterface::class => ProfileRepository::class,
        MeasureInterface::class => MeasureRepository::class,
        ObjectiveLevelInterface::class => ObjectiveLevelRepository::class,
        OkrPotentialInterface::class => OkrPotentialRepository::class,
        UserInterface::class => UserRepository::class,
        ObjectiveInterface::class => ObjectiveRepository::class,
        KeyResultInterface::class => KeyResultRepository::class,
        OKRInterface::class => OKRRepository::class,
        PeriodTimeInterface::class => PeriodTimeRepository::class,
        UserCustomInterface::class => UserCustomRepository::class,
        OrganizationTeamInterface::class => OrganizationTeamRepository::class,
        DurationPerformanceInterface::class => DurationPerformanceRepository::class,
        ProfileSettingInterface::class => ProfileSettingRepository::class,
        ProfileFieldSettingInterface::class => ProfileFieldSettingRepository::class,
        FrequencyPeriodInterface::class => FrequencyPeriodRepository::class,
        VisibilityInterface::class => VisibilityRepository::class,
        HumanResourceInterface::class => HumanResourceRepository::class,
        FrequencyReviewInterface::class => FrequencyReviewRepository::class,
        PerformanceInterface::class => PerformanceRepository::class,
        TaskInterface::class => TaskRepository::class,
        ConversationInterface::class => ConversationRepository::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->providers as $key => $item) {
            $this->app->bind($key, $item);
        }
    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
