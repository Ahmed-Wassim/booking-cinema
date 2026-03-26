<?php

declare(strict_types=1);

namespace App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Services\Classes;

use App\Domain\Landlord\Enums\RegistrationRequestStatusEnum;
use App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Repositories\Interfaces\IRegistrationRequestRepository;
use App\Domain\Landlord\Dashboard\Web\RegistrationRequest\Services\Interfaces\IRegistrationRequestService;
use Exception;
use Illuminate\Support\Facades\Hash;

class RegistrationRequestService implements IRegistrationRequestService
{
    public function __construct(protected IRegistrationRequestRepository $registrationRequestRepository
    ) {}

    public function storeRequest(array $data)
    {
        try {
            $centralDomain = config('tenancy.central_domains')[0] ?? 'cinema.test';
            $fullDomain = $data['domain'].'.'.$centralDomain;

            $request = $this->registrationRequestRepository->create([
                'company_name' => $data['company_name'],
                'domain' => $fullDomain,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'status' => RegistrationRequestStatusEnum::PENDING,
            ]);

            return $request;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
