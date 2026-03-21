<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Dashboard\Api\Providers\Injectors;

use App\Domain\Tenant\Dashboard\Api\Branch\Repositories\Classes\BranchRepository;
use App\Domain\Tenant\Dashboard\Api\Branch\Repositories\Interfaces\IBranchRepository;
use App\Domain\Tenant\Dashboard\Api\Hall\Repositories\Classes\HallRepository;
use App\Domain\Tenant\Dashboard\Api\Hall\Repositories\Interfaces\IHallRepository;
use App\Domain\Tenant\Dashboard\Api\HallSection\Repositories\Classes\HallSectionRepository;
use App\Domain\Tenant\Dashboard\Api\HallSection\Repositories\Interfaces\IHallSectionRepository;
use App\Domain\Tenant\Dashboard\Api\PriceTier\Repositories\Classes\PriceTierRepository;
use App\Domain\Tenant\Dashboard\Api\PriceTier\Repositories\Interfaces\IPriceTierRepository;
use App\Domain\Tenant\Dashboard\Api\Seat\Repositories\Classes\SeatRepository;
use App\Domain\Tenant\Dashboard\Api\Seat\Repositories\Interfaces\ISeatRepository;
use App\Domain\Tenant\Dashboard\Api\User\Repositories\Classes\UserRepository;
use App\Domain\Tenant\Dashboard\Api\User\Repositories\Interfaces\IUserRepository;
use App\Models\Tenant\Branch;
use App\Models\Tenant\Hall;
use App\Models\Tenant\HallSection;
use App\Models\Tenant\PriceTier;
use App\Models\Tenant\Seat;
use App\Models\Tenant\Movie;
use App\Models\Tenant\Showtime;
use App\Domain\Tenant\Dashboard\Api\Movie\Repositories\Classes\MovieRepository;
use App\Domain\Tenant\Dashboard\Api\Movie\Repositories\Interfaces\IMovieRepository;
use App\Domain\Tenant\Dashboard\Api\Showtime\Repositories\Classes\ShowtimeRepository;
use App\Domain\Tenant\Dashboard\Api\Showtime\Repositories\Interfaces\IShowtimeRepository;
use App\Domain\Tenant\Dashboard\Api\ShowtimeSeat\Repositories\Classes\ShowtimeSeatRepository;
use App\Domain\Tenant\Dashboard\Api\ShowtimeSeat\Repositories\Interfaces\IShowtimeSeatRepository;
use App\Models\Tenant\ShowtimeSeat;
use App\Providers\AppServiceProvider;

class TenantRepositoriesInjector extends AppServiceProvider
{
    public function boot(): void
    {
        $this->app->scoped(IBranchRepository::class, function () {
            return new BranchRepository(new Branch());
        });

        $this->app->scoped(IHallRepository::class, function () {
            return new HallRepository(new Hall());
        });

        $this->app->scoped(IPriceTierRepository::class, function () {
            return new PriceTierRepository(new PriceTier());
        });

        $this->app->scoped(IHallSectionRepository::class, function () {
            return new HallSectionRepository(new HallSection());
        });

        $this->app->scoped(ISeatRepository::class, function () {
            return new SeatRepository(new Seat());
        });

        $this->app->scoped(IUserRepository::class, function () {
            return new UserRepository(new \App\Models\Tenant\User());
        });

        $this->app->scoped(IMovieRepository::class, function () {
            return new MovieRepository(new Movie());
        });

        $this->app->scoped(IShowtimeRepository::class, function () {
            return new ShowtimeRepository(new Showtime());
        });

        $this->app->scoped(IShowtimeSeatRepository::class, function () {
            return new ShowtimeSeatRepository(new ShowtimeSeat());
        });
    }
}
