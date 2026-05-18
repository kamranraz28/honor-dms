<?php

namespace App\Services;

use App\Repositories\DistrictRepository;
use App\Repositories\DivisionRepository;
use App\Repositories\UpazilaRepository;
use App\Repositories\UserRepository;
use App\District;
use App\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class RetailerService
{
    protected $users;
    protected $divisions;
    protected $districts;
    protected $upazilas;

    public function __construct(
        UserRepository $users,
        DivisionRepository $divisions,
        DistrictRepository $districts,
        UpazilaRepository $upazilas
        )
    {
        $this->users = $users;
        $this->divisions = $divisions;
        $this->districts = $districts;
        $this->upazilas = $upazilas;
    }

    public function getRetailerPageData()
    {
        return [
            'users' => $this->users->paginateRetailers(),
        ];
    }

    public function store(array $data, Request $request): void
    {
        // Auto increment (if really needed)
        $statement = DB::select("show table status like 'users'");
        $ainid = $statement[0]->Auto_increment;

        // Handle image upload
        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imageName = time()
                . mt_rand()
                . '.' . $image->getClientOriginalExtension();

            Storage::put($imageName, file_get_contents($image));
        }

        // Prepare data
        $data['password'] = bcrypt($data['password']);
        $data['photo'] = $imageName;
        $data['level'] = 200;
        $data['remember_token'] = csrf_token();

        unset($data['confirm_password']);

        // Create user via repository
        $this->users->create($data);
    }
}
