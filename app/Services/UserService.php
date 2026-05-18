<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\DivisionRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\UpazilaRepository;
use Illuminate\Support\Facades\Cache;

class UserService
{
    protected $users;
    protected $divisions;
    protected $districts;
    protected $upazilas;
    protected $excelService;

    public function __construct(
        UserRepository $users,
        DivisionRepository $divisions,
        DistrictRepository $districts,
        UpazilaRepository $upazilas,
        ExcelDownloadService $excelService
    ) {
        $this->users = $users;
        $this->divisions = $divisions;
        $this->districts = $districts;
        $this->upazilas = $upazilas;
        $this->excelService = $excelService;
    }

    // ✔ Basic find
    public function find($id)
    {
        return $this->users->find($id);
    }

    // ✔ Full UserView data (optimized + cached)
    public function getUserPageData()
    {
        return [
            'users'   => $this->users->paginateNonSpecialUsers(),
            'summary' => $this->users->getUserSummaryByLevel(),
        ];
    }

    public function create()
    {
        return [
            'divisions' => $this->divisions->all(),
            'districts' => $this->districts->all(),
            'upazilas'  => $this->upazilas->all(),
        ];
    }

    public function storeUser($request)
    {
        // Step 2 — Image upload
        $imageName = null;
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:200000',
            ]);

            $imageName = $this->users->uploadImage($request->file('image'));
        }

        // Step 3 — Prepare data
        $data              = $request->all();
        $data['password']  = bcrypt($data['password']);
        $data['photo']     = $imageName;
        $data['remember_token'] = $request->_token;

        // Step 4 — Insert user
        return $this->users->create($data);
    }

    public function updateUser($id, array $data)
    {
         // Step 2 — Image upload
        $imageName = null;
        if (isset($data['image'])) {
            $imageName = $this->users->uploadImage($data['image']);
            unset($data['image']);
        }
        if ($imageName) {
            $data['photo'] = $imageName;
        }
        return $this->users->update($id, $data);
    }

    public function userDownload()
    {
        $exportData = [];

        $users = $this->users->getUsers();

        // Level mapping
        $levelNames = [
            100  => 'Distributor',
            10   => 'TSO / TSM',
            5    => 'Service Center',
            6    => 'Warehouse',
            7    => 'Accounts',
            300  => 'Mid Management',
            400  => 'Top Management',
            1000 => 'Huawei',
        ];

        foreach ($users as $user) {

            $exportData[] = [
                'Action' => '',
                'Level'         => $levelNames[$user->level] ?? '-',
                'Category' => $user->dis_cat ?? '-',
                'Name' => $user->firstname ?? '-',
                'Email'         => $user->email ?? '-',
                'Alternative Email'         => $user->alemail ?? '-',
                'Office ID'   => $user->officeid ?? '-',
                'Contact Name'  => $user->contact_name ?? '-',
                'Contact No.'   => $user->contact ?? '-',
                'Division'      => optional($user->division)->name ?? '-',
                'District'      => optional($user->district)->name ?? '-',
                'Upazila'       => optional($user->upazila)->name ?? '-',
                'Address' => $user->address ?? '-',
                'Password' => 'Change',
                'Status'        => $user->active == 1 ? 'Active' : 'Inactive',
                'Return'        => $user->status == 1 ? 'Enable' : 'Disable',
                'Photo' => 'No File',
                'NID' => 'No File',
            ];
        }

        return $this->excelService->download('AllUser', $exportData);
    }


    public function inactiveRetailers()
    {
        return $this->users->getInactiveRetailers();
    }
    public function updateUserId(array $data)
    {
        $id = $data['id'];
        $officeId = $data['officeid'];
        return $this->users->updateOfficeId($id, $officeId);
    }
    public function updateUserPassword(array $data)
    {
        $id = $data['id'];
        $password = $data['password'];
        return $this->users->updatePassword($id, $password);
    }
    public function changeUserStatus(int $id)
    {
        return $this->users->changeStatus($id);
    }
    public function changeUserAbleStatus(int $id)
    {
        return $this->users->changeAble($id);
    }
    public function deleteUser(int $id)
    {
        return $this->users->delete($id);
    }
    public function retailerDownload()
    {
        $exportData = [];

        $retailers = $this->users->allRetailers();

        foreach($retailers as $retailer) {
            $exportData[] = [
                'Retailer Name' => $retailer->firstname ?? '-',
                'Contact Name' => $retailer->contact_name ?? '-',
                'Market Name' => $retailer->market_name ?? '-',
                'Email' => $retailer->email ?? '-',
                'Retailer ID' => $retailer->officeid ?? '-',
                'Retailer_Type' => $retailer->store_type ?? '-',
                'Contact No.' => $retailer->contact ?? '-',
                'Division' => optional($retailer->division)->name ?? '-',
                'District' => optional($retailer->district)->name ?? '-',
                'Upazila' => optional($retailer->upazila)->name ?? '-',
                'Level' => 'Retailer',
                'Status' => $retailer->active == 1 ? 'Active' : 'Inactive',
            ];
        }
        return $this->excelService->download('AllRetailer',$exportData);
    }

}
