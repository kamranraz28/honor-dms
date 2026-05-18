<?php

namespace App\Repositories;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UserRepository
{
    public function all(): Collection
    {
        return User::with('division', 'district', 'upazila')
            ->orderBy('id', 'desc')
            ->get();
    }
    public function find(int $id)
    {
        return User::findOrFail($id);
    }
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }
    public function findByToken($token)
    {
        return User::where('remember_token', $token)->first();
    }
    public function findUserByCode(string $code): User
    {
        return $this->findByCodeAndLevel($code);
    }

    public function allRetailers()
    {
        return User::with('division','district','upazila')
            ->where('level',200)
            ->get();
    }

    public function getUsers()
    {
        return User::with('division','district','upazila')
            ->whereNotIn('level',[200, 500, 2000])
            ->get();
    }

    public function findRetailerByCode(string $code): User
    {
        return $this->findByCodeAndLevel($code, 200);
    }

    public function getRetailersByCodes(array $codes)
    {
        return User::where('level', 200)
            ->whereIn('officeid', $codes)
            ->get()
            ->keyBy('officeid');
    }

    public function findDistributorByCode(string $code): User
    {
        return $this->findByCodeAndLevel($code, 100);
    }

    public function ensureNoUserByCode(string $code): void
    {
        $this->ensureNotExists($code);
    }

    public function ensureNoRetailerByCode(string $code): void
    {
        $this->ensureNotExists($code, 200);
    }

    public function ensureNoDistributorByCode(string $code): void
    {
        $this->ensureNotExists($code, 100);
    }

    public function getDistributors(?int $distributorId = null)
    {
        if ($distributorId) {
            return collect([$this->find($distributorId)]);
        }

        return User::where('level', 100)
            ->select('id', 'firstname', 'officeid')
            ->get();
    }

    public function getRetailers(?int $retailerId = null)
    {
        if ($retailerId) {
            return collect([$this->find($retailerId)]);
        }

        return User::where('level', 200)
            ->select('id', 'firstname', 'officeid')
            ->get();
    }


    public function getDistributorName($id)
    {
        if (!$id) {
            return "All Distributors";
        }

        $user = User::find($id);
        return "{$user->firstname} - {$user->officeid}";
    }


    public function create(array $data): User
    {
        return User::create($data);
    }
    public function update(int $id, array $data): User
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }

    public function uploadImage($image)
    {
        $imageName = time() . mt_rand() . '.' . $image->getClientOriginalExtension();
        Storage::put($imageName, file_get_contents($image));

        return $imageName;
    }

    /* =======================
       Internal helper methods
       ======================= */

    private function findByCodeAndLevel(string $code, ?int $level = null): User
    {
        $query = User::where('officeid', $code);

        if ($level !== null) {
            $query->where('level', $level);
        }

        $user = $query->first();

        if (!$user) {
            throw new \Exception("User '{$code}' not found in the system.");
        }

        return $user;
    }

    private function ensureNotExists(string $code, ?int $level = null): void
    {
        $query = User::where('officeid', $code);

        if ($level !== null) {
            $query->where('level', $level);
        }

        if ($query->exists()) {
            throw new \Exception("User '{$code}' already exists in the system.");
        }
    }

    public function countPendingRetailers()
    {
        return User::where([
            'active' => 0,
            'status' => 0,
            'level' => 200,
        ])->count();
    }

    public function paginateRetailers()
    {
        return User::select([
            'id',
            'firstname',
            'email',
            'level',
            'officeid',
            'active',
            'division_id',
            'district_id',
            'upazila_id',
            'contact',
            'contact_name',
            'photo',
            'status',
            'market_name',
            'store_type',
        ])
            ->with([
                'division:id,name',
                'district:id,name',
                'upazila:id,name',
            ])
            ->where('level', 200)
            ->orderBy('id', 'desc')
            ->simplePaginate(50); // 🔥 IMPORTANT
    }

    public function paginateNonSpecialUsers()
    {
        return User::select([
            'id',
            'firstname',
            'email',
            'level',
            'officeid',
            'active',
            'division_id',
            'district_id',
            'upazila_id',
            'contact',
            'contact_name',
            'photo',
            'nidimage',
            'dis_cat',
            'address',
            'status',
        ])
            ->with([
                'division:id,name',
                'district:id,name',
                'upazila:id,name',
            ])
            ->whereNotIn('level', [500, 200, 2000])
            ->orderBy('id', 'desc')
            ->simplePaginate(50); // 🔥 IMPORTANT
    }

    public function getUserSummaryByLevel()
    {
        return User::select(
                'level',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(active = 1) as active'),
                DB::raw('SUM(active = 0) as inactive')
            )
            ->whereNotIn('level', [2000, 200, 500])
            ->groupBy('level')
            ->orderBy('level')
            ->get()
            ->keyBy('level');
    }


    public function destroy(int $id)
    {
        $user = $this->find($id);
        $user->delete();
    }

    public function getInactiveRetailers()
    {
        return User::with('division', 'district', 'upazila')->where([
            'active' => 0,
            'status' => 0,
            'level' => 200,
        ])->get();
    }

    public function updateOfficeId($id, $code)
    {
        $user = $this->find($id);
        $user->officeid = $code;
        $user->save();
    }
    public function updatePassword($id, $password)
    {
        $user = $this->find($id);
        $user->password = bcrypt($password);
        $user->save();
    }
    public function changeStatus(int $id)
    {
        $user = $this->find($id);
        if ($user->active == true) {
            $user->active = false;
        } else {
            $user->active = true;
        }
        return $user->save();
    }

    public function changeAble(int $id)
    {
        $user = $this->find($id);
        if ($user->status == 1) {
            $user->status = 0;
        } else {
            $user->status = 1;
        }
        return $user->save();
    }
    public function delete(int $id)
    {
        $user = $this->find($id);
        return $user->delete();
    }

}
