<?php

namespace App\Services;

use App\Helpers\DateHelper;
use App\JobProgress;
use App\Jobs\PrimarySalesBulkUpload;
use App\Jobs\SecondarySalesUpload;
use App\Jobs\StockBulkUpload;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\ImeiUploadRepository;
use App\Repositories\PrimarySaleRepository;
use App\Repositories\PrimaryTransferRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ReplaceRepository;
use App\Repositories\RetailerRepository;
use App\Repositories\SecondarySaleRepository;
use App\Repositories\StockRepository;
use App\Repositories\TertiarySaleRepository;
use App\Repositories\TsoRepository;
use App\Repositories\UpazilaRepository;
use App\Repositories\UserRepository;
use App\Smsdetail;
use Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BulkUploadService
{
    protected $brandRepository;
    protected $categoryRepository;
    protected $userRepository;
    protected $districtRepository;
    protected $upazilaRepository;
    protected $productRepository;
    protected $stockRepository;
    protected $tertiarySaleRepository;
    protected $retailerRepository;
    protected $primarySaleRepository;
    protected $primaryTransferRepository;
    protected $secondarySaleRepository;
    protected $tsoRepository;
    protected $replaceRepository;
    protected $imeiUploadRepository;
    public function __construct(
        BrandRepository $brandRepository,
        CategoryRepository $categoryRepository,
        UserRepository $userRepository,
        DistrictRepository $districtRepository,
        UpazilaRepository $upazilaRepository,
        ProductRepository $productRepository,
        StockRepository $stockRepository,
        TertiarySaleRepository $tertiarySaleRepository,
        RetailerRepository $retailerRepository,
        PrimarySaleRepository $primarySaleRepository,
        PrimaryTransferRepository $primaryTransferRepository,
        SecondarySaleRepository $secondarySaleRepository,
        TsoRepository $tsoRepository,
        ImeiUploadRepository $imeiUploadRepository,
        ReplaceRepository $replaceRepository
    ) {
        $this->brandRepository = $brandRepository;
        $this->categoryRepository = $categoryRepository;
        $this->userRepository = $userRepository;
        $this->districtRepository = $districtRepository;
        $this->upazilaRepository = $upazilaRepository;
        $this->productRepository = $productRepository;
        $this->stockRepository = $stockRepository;
        $this->tertiarySaleRepository = $tertiarySaleRepository;
        $this->retailerRepository = $retailerRepository;
        $this->primarySaleRepository = $primarySaleRepository;
        $this->primaryTransferRepository = $primaryTransferRepository;
        $this->secondarySaleRepository = $secondarySaleRepository;
        $this->tsoRepository = $tsoRepository;
        $this->imeiUploadRepository = $imeiUploadRepository;
        $this->replaceRepository = $replaceRepository;
    }
    public function handle($type, UploadedFile $file, $user)
    {
        $rows = $this->readCsv($file->getRealPath());
        DB::beginTransaction();

        try {
            switch ($type) {
                case 1:
                    $this->uploadBrands($rows);
                    DB::commit();
                    return 'Brand CSV uploaded successfully.';
                case 2:
                    $this->uploadCategories($rows);
                    DB::commit();
                    return 'Category CSV uploaded successfully.';
                case 3:
                    $this->uploadProducts($rows);
                    DB::commit();
                    return 'Product CSV uploaded successfully.';
                case 5:
                    $this->dispatchStockBulkUpload($file, $user);
                    DB::commit();
                    return 'Stock upload queued successfully.';
                case 6:
                    $this->uploadRetailers($rows);
                    DB::commit();
                    return 'Retailer CSV uploaded successfully.';
                case 61:
                    $this->uploadDistributors($rows);
                    DB::commit();
                    return 'Distributor CSV uploaded successfully.';
                case 7:
                    $this->uploadTertiarySales($rows);
                    DB::commit();
                    return 'Tertiary Sales CSV uploaded successfully.';
                case 9:
                    $this->uploadDistributorRetailerMapping($rows);
                    DB::commit();
                    return 'Distributor-Retailer Mapping CSV uploaded successfully.';
                case 10:
                    $this->dispatchPrimarySalesBulkUpload($file, $user);
                    DB::commit();
                    return 'Primary Sales upload queued successfully.';
                case 12:
                    $this->uploadSecondarySales($file, $user);
                    DB::commit();
                    return 'Secondary Sales upload queued successfully.';
                case 14:
                    $this->uploadDistributorImeiTransfer($rows);
                    DB::commit();
                    return 'Distributor IMEI Transfer CSV uploaded successfully.';
                case 15:
                    $this->uploadRetailerDeletePermanently($rows);
                    DB::commit();
                    return 'Retailer Delete Permanently CSV uploaded successfully.';
                case 100:
                    $this->uploadRetailerImeiTransfer($rows);
                    DB::commit();
                    return 'Retailer IMEI Transfer CSV uploaded successfully.';
                case 104:
                    $this->uploadWarrantyUpdate($rows);
                    DB::commit();
                    return 'Warranty Period Update CSV uploaded successfully.';
                case 106:
                    $this->uploadRetailerUnmapping($rows);
                    DB::commit();
                    return 'Retailer Unmapping CSV uploaded successfully.';
                case 16:
                    $this->uploadPrimarySalesDelete($rows);
                    DB::commit();
                    return 'Primary Sales Delete CSV uploaded successfully.';
                case 17:
                    $this->uploadSecondarySalesDelete($rows);
                    DB::commit();
                    return 'Secondary Sales Delete CSV uploaded successfully.';
                case 18:
                    $this->uploadStockDelete($rows);
                    DB::commit();
                    return 'Stock Delete CSV uploaded successfully.';
                case 19:
                    $this->uploadTSOs($rows);
                    DB::commit();
                    return 'TSO/TSM CSV uploaded successfully.';
                case 21:
                    $this->uploadTSOMapping($rows);
                    DB::commit();
                    return 'TSO/TSM Mapping CSV uploaded successfully.';
                case 22:
                    $this->uploadTSOUnMapping($rows);
                    DB::commit();
                    return 'TSO/TSM Un-Mapping CSV uploaded successfully.';
                case 23:
                    $this->uploadTertiarySaleDelete($rows);
                    DB::commit();
                    return 'Tertiary Sale Deleted successfully.';
                case 30:
                    $this->uploadTemporaryTableDataDelete($rows);
                    DB::commit();
                    return 'CSV Uploaded successfully.';
                case 202:
                    $this->uploadInactiveRetail($rows);
                    DB::commit();
                    return 'Retailers Inactivated successfully.';
                case 204:
                    $this->uploadReplaceRequestDelete($rows);
                    DB::commit();
                    return 'Replace Requuests deleted successfully.';
                case 206:
                    $this->uploadReplaceImeiChange($rows);
                    DB::commit();
                    return 'Replaced IMEI updated successfully.';
                case 207:
                    $this->uploadReplaceImeiReceive($rows);
                    DB::commit();
                    return 'Replaced IMEI updated to Received successfully.';
                case 208:
                    $this->uploadIMEIChange($rows);
                    DB::commit();
                    return 'IMEI updated Successfully.';
                case 209:
                    $this->stockProductChange($rows);
                    DB::commit();
                    return 'Stock Product Updated Successfully.';

                default:
                    throw new \Exception("Unsupported upload type: {$type}");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Read CSV file and return rows (without header)
     */
    private function readCsv($path)
    {
        $data = array_map('str_getcsv', file($path, FILE_SKIP_EMPTY_LINES));
        return array_slice($data, 1);
    }

    /**
     * TYPE = 1 → Brand Upload
     */
    private function uploadBrands(array $rows)
    {
        foreach ($rows as $index => $row) {
            $name = trim($row[0]);

            $this->brandRepository->ensureNotAvailableByName($name);

            $this->brandRepository->create([
                'name' => $name
            ]);
        }
    }

    /**
     * TYPE = 2 → Category Upload
     */
    private function uploadCategories(array $rows)
    {
        foreach ($rows as $index => $row) {
            $name = $row[0];

            $this->categoryRepository->ensureNotAvailableByName($name);

            $this->categoryRepository->create([
                'name' => $name
            ]);
        }
    }

    /**
     * TYPE = 3 → Product Upload
     */
    private function uploadProducts(array $rows)
    {
        foreach ($rows as $index => $row) {

            $productName = trim($row[0]);
            $model = trim($row[1]);
            $code = trim($row[2]);
            $color = trim($row[3]);
            $brandName = trim($row[4]);
            $catName = trim($row[5]);
            $details = trim($row[6]);
            $chalanType = trim($row[7]);
            $dp = trim($row[8]);

            //Product Lookup
            $this->productRepository->ensureNotAvailableByModel($model);

            // Brand lookup
            $brand = $this->brandRepository->findByName($brandName);

            // Category lookup
            $cat = $this->categoryRepository->findByName($catName);

            $this->productRepository->create([
                'name' => $productName,
                'model' => $model,
                'product_code' => $code,
                'color' => $color,
                'brand_id' => $brand->id,
                'cat_id' => $cat->id,
                'details' => $details,
                'chalan_type' => $chalanType,
                'dp' => $dp,
            ]);
        }
    }

    /**
     * TYPE = 5 → Stock Upload
     */
    private function dispatchStockBulkUpload(UploadedFile $file, $user)
    {
        // Store file
        $csvPath = $file->store('upload/stock_uploads', 'local');

        // Generate job UUID
        $jobId = (string) Str::uuid();

        // Create job tracking record
        JobProgress::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
            'type' => 'stock_bulk_upload',
            'status' => 'queued',
            'message' => 'Job is queued for processing.',
        ]);

        // Dispatch queue job
        StockBulkUpload::dispatch($csvPath, $jobId);
    }

    /**
     * TYPE = 6 → Retailer Upload
     */
    private function uploadRetailers(array $rows)
    {
        foreach ($rows as $index => $row) {
            $officeId = trim($row[0]);
            $firstname = $row[1];
            $contactNumber = trim($row[2]);
            $contactName = $row[3];
            $email = trim($row[4]);
            $districtName = trim($row[5]);
            $upazilaName = trim($row[6]);
            $address = $row[7];
            $storeType = $row[8];
            $marketName = $row[9];

            // User lookup
            $this->userRepository->ensureNoRetailerByCode($officeId);

            // District lookup
            $district = $this->districtRepository->findByName($districtName);

            // Upazila lookup
            $upazila = $this->upazilaRepository->findByName($upazilaName);

            $this->userRepository->create([
                'officeid' => $officeId,
                'password' => Hash::make($officeId),
                'firstname' => $firstname,
                'contact' => $contactNumber,
                'contact_name' => $contactName,
                'email' => $email,
                'division_id' => $district->division_id,
                'district_id' => $district->id,
                'upazila_id' => $upazila->id,
                'address' => $address,
                'storetype' => $storeType,
                'market_name' => $marketName,
                'level' => 200,
            ]);
        }
    }

    /**
     * TYPE = 61 → Distributor Upload
     */
    private function uploadDistributors(array $rows)
    {
        foreach ($rows as $index => $row) {
            $officeId = trim($row[0]);
            $firstname = $row[1];
            $contactName = $row[2];
            $email = trim($row[3]);
            $districtName = trim($row[4]);
            $upazilaName = trim($row[5]);
            $address = $row[6];
            $category = $row[7];

            // User lookup
            $this->userRepository->ensureNoDistributorByCode($officeId);

            // District lookup
            $district = $this->districtRepository->findByName($districtName);

            // Upazila lookup
            $upazila = $this->upazilaRepository->findByName($upazilaName);

            $this->userRepository->create([
                'officeid' => $officeId,
                'password' => Hash::make($officeId),
                'firstname' => $firstname,
                'contact_name' => $contactName,
                'email' => $email,
                'division_id' => $district->division_id,
                'district_id' => $district->id,
                'upazila_id' => $upazila->id,
                'address' => $address,
                'dis_cat' => $category,
                'level' => 100
            ]);
        }
    }

    /**
     * TYPE = 7 → Tertiary Sales Upload
     */
    private function uploadTertiarySales(array $rows)
    {
        // STEP 1: Collect retailer codes & IMEIs
        $retailerCodes = [];
        $imeiList = [];

        foreach ($rows as $row) {
            $retailerCodes[] = trim($row[0]);
            $imeiList[] = trim($row[1]);
        }

        // STEP 2: Bulk fetch retailers, stocks & existing tertiary sales
        $retailers = $this->userRepository
            ->getRetailersByCodes($retailerCodes);

        $stocks = $this->stockRepository
            ->getStocksByIMEIs($imeiList)
            ->keyBy('sno'); // Keyed by IMEI1

        $existingSales = $this->tertiarySaleRepository
            ->getSalesByIMEIs($imeiList)
            ->keyBy('sno'); // Prevent duplicates

        $insertData = [];

        // STEP 3: Prepare batch insert array
        foreach ($rows as $row) {
            $retailerCode = trim($row[0]);
            $imei1 = trim($row[1]);
            $imei2 = trim($row[2]);
            $mobile = trim($row[3]);
            $date = isset($row[4]) ? DateHelper::parseCsvDate($row[4]) : now();

            // VALIDATION
            if (!isset($retailers[$retailerCode])) {
                continue; // Retailer missing → skip row
            }
            if (!isset($stocks[$imei1])) {
                continue; // Stock missing → skip row
            }
            if (isset($existingSales[$imei1])) {
                continue; // Already exists → skip row
            }

            $stock = $stocks[$imei1];
            $retailer = $retailers[$retailerCode];

            $insertData[] = [
                'sno'            => $imei1,
                'imei'           => $imei2,
                'product_id'     => $stock->product_id,
                'brand_id'       => $stock->brand_id,
                'wperiod'        => $stock->wperiod,
                'user_id'        => $retailer->id,
                'mobile'         => $mobile,
                'promo_id'       => 0,
                'promodetail_id' => 0,
                'status'         => 0,
                'created_at'     => $date,
                'updated_at'     => $date,
            ];
        }

        // STEP 4: Batch insert (super fast)
        if (!empty($insertData)) {
            $this->tertiarySaleRepository->insertMany($insertData);
        }
    }


    /**
     * TYPE = 9 → Distributor-Retailer Mapping Upload
     */
    private function uploadDistributorRetailerMapping(array $rows)
    {
        foreach ($rows as $index => $row) {
            $distributorCode = trim($row[0]);
            $retailerCode = trim($row[1]);

            $distributor = $this->userRepository->findDistributorByCode($distributorCode);

            $retailer = $this->userRepository->findRetailerByCode($retailerCode);

            $this->retailerRepository->create([
                'retailer_id' => $retailer->id,
                'user_id' => $distributor->id,
                'name' => $retailer->firstname,
                'email' => $retailer->email,
                'officeid' => $retailer->officeid
            ]);
        }
    }

    /**
     * TYPE = 10 → Primary Sales Upload
     */
    private function dispatchPrimarySalesBulkUpload(UploadedFile $file, $user)
    {
        // Store file
        $csvPath = $file->store('upload/primary_bulk_upload', 'local');

        // Generate job UUID
        $jobId = (string) Str::uuid();

        // Create job tracking record
        JobProgress::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
            'type' => 'primary_bulk_upload',
            'status' => 'queued',
            'message' => 'Job is queued for processing.',
        ]);

        // Dispatch queue job
        PrimarySalesBulkUpload::dispatch($csvPath, $jobId);
    }

    /**
     * TYPE = 12 → Secondary Sales Upload
     */
    private function uploadSecondarySales(UploadedFile $file, $user)
    {
        // Store file
        $csvPath = $file->store('upload/primary_bulk_upload', 'local');

        // Generate job UUID
        $jobId = (string) Str::uuid();

        // Create job tracking record
        JobProgress::create([
            'user_id' => $user->id,
            'job_id' => $jobId,
            'type' => 'secondary_bulk_upload',
            'status' => 'queued',
            'message' => 'Job is queued for processing.',
        ]);

        // Dispatch queue job
        SecondarySalesUpload::dispatch($csvPath, $jobId);
    }

    /**
     * TYPE = 14 → Distributor IMEI Transfer Upload
     */
    private function uploadDistributorImeiTransfer(array $rows)
    {
        foreach ($rows as $index => $row) {
            $distributorCode = $row[0];
            $imei = $row[1];

            // Distributor lookup
            $distributor = $this->userRepository->findDistributorByCode($distributorCode);

            //Primary Sales Lookup
            $priSale = $this->primarySaleRepository->findByIMEI($imei);

            $this->primaryTransferRepository->create([
                'old_user_id' => $priSale->user_id,
                'new_user_id' => $distributor->id,
                'imei1' => $priSale->sno,
                'imei2' => $priSale->imei,
                'transfered_by' => auth()->id()
            ]);

            $priSale->update([
                'user_id' => $distributor->id,
                'dis_id' => $distributor->dis_id,
                'up_id' => $distributor->up_id
            ]);
        }
    }

    /**
     * TYPE = 15 → Retailer Delete Permanently Upload
     */
    private function uploadRetailerDeletePermanently(array $rows)
    {
        foreach ($rows as $index => $row) {
            $retailerCode = $row[0];

            // Retailer lookup
            $retailer = $this->userRepository->findRetailerByCode($retailerCode);
            // Delete retailer mappings
            $this->retailerRepository->deleteByRetailerId($retailer->id);

            // Delete retailer
            $this->userRepository->destroy($retailer->id);
        }
    }

    /**
     * TYPE = 100 → Retailer IMEI Transfer Upload
     */
    private function uploadRetailerImeiTransfer(array $rows)
    {
        foreach ($rows as $index => $row) {
            $retailerCode = $row[0];
            $imei = $row[1];

            // Retailer lookup
            $retailer = $this->userRepository->findRetailerByCode($retailerCode);

            $retailMap = $this->retailerRepository->findRetailerByCode($retailerCode);

            //Secondary Sales Lookup
            $secSale = $this->secondarySaleRepository->findByIMEI($imei);

            $secSale->update([
                'user_id' => $retailMap->user_id,
                'ruser_id' => $retailer->id,
                'retailer_id' => $retailMap->id,
                'dis_id' => $retailer->dis_id,
                'up_id' => $retailer->up_id,
                'updated_at' => now()
            ]);

        }
    }

    /**
     * TYPE = 104 → Warranty Period Updating Upload
     */
    private function uploadWarrantyUpdate(array $rows)
    {
        foreach ($rows as $index => $row) {
            $imei = trim($row[0]);
            $warranty = trim($row[1]);

            $stock = $this->stockRepository->findByIMEI($imei);
            $stock->update([
                'wperiod' => $warranty
            ]);

            $terSale = Smsdetail::where('sno', $imei)->orWhere('imei', $imei)->first();
            if ($terSale) {
                $terSale->update([
                    'wperiod' => $warranty
                ]);
            }
        }
    }

    /**
     * TYPE = 106 → Retailer Unmapping Upload
     */
    private function uploadRetailerUnmapping(array $rows)
    {
        foreach ($rows as $index => $row) {
            $distributorCode = trim($row[0]);
            $retailerCode = trim($row[1]);

            // Distributor lookup
            $distributor = $this->userRepository->findDistributorByCode($distributorCode);

            $retailer = $this->userRepository->findRetailerByCode($retailerCode);

            $this->retailerRepository->destroy($distributor->id, $retailer->id);
        }
    }

    /**
     * TYPE = 16 → Primary Sales Delete Upload
     */
    private function uploadPrimarySalesDelete(array $rows)
    {
        foreach ($rows as $index => $row) {
            $distributorCode = trim($row[0]);
            $imei = trim($row[1]);

            $distributor = $this->userRepository->findDistributorByCode($distributorCode);

            $this->stockRepository->findByIMEI($imei);
            $this->primarySaleRepository->findByDistributorAndIMEI($distributor->id, $imei);
            $this->secondarySaleRepository->ensureNotAvailableByIMEI($imei);

            $this->primarySaleRepository->destroy($imei);
        }
    }

    /**
     * TYPE = 17 → Secondary Sales Delete Upload
     */
    private function uploadSecondarySalesDelete(array $rows)
    {
        foreach ($rows as $index => $row) {
            $retailerCode = trim($row[0]);
            $imei = trim($row[1]);

            $retailer = $this->userRepository->findRetailerByCode($retailerCode);

            $this->stockRepository->findByIMEI($imei);
            $this->tertiarySaleRepository->ensureNotAvailableByIMEI($imei);

            $this->secondarySaleRepository->destroy($retailer->id, $imei);
        }
    }

    /**
     * TYPE = 18 → Stocks Delete Upload
     */
    private function uploadStockDelete(array $rows)
    {
        foreach ($rows as $index => $row) {
            $imei = trim($row[0]);
            $this->stockRepository->destroy($imei);
        }
    }

    /**
     * TYPE = 19 → TSO/TSM Upload
     */
    private function uploadTSOs(array $rows)
    {
        foreach ($rows as $index => $row) {
            $tsoCode = trim($row[0]);
            $tsoName = $row[1];
            $contactNumber = trim($row[2]);
            $email = trim($row[3]);
            $districtName = trim($row[4]);
            $upazilaName = trim($row[5]);

            //User Lookup
            $this->userRepository->ensureNoUserByCode($tsoCode);

            //District Lookup
            $district = $this->districtRepository->findByName($districtName);

            //Upazila Lookup
            $upazila = $this->upazilaRepository->findByName($upazilaName);

            $this->userRepository->create([
                'officeid' => $tsoCode,
                'password' => $tsoCode,
                'firstname' => $tsoName,
                'contact' => $contactNumber,
                'email' => $email,
                'division_id' => $district->division_id,
                'district_id' => $district->id,
                'upazila_id' => $upazila->id,
                'level' => 10
            ]);

        }
    }

    /**
     * TYPE = 21 → TSO/TSM-Distributor Mapping Upload
     */
    private function uploadTSOMapping(array $rows)
    {
        foreach ($rows as $index => $row) {
            $distributorCode = trim($row[0]);
            $tsoCode = trim($row[1]);

            //Distributor Lookup
            $distributor = $this->userRepository->findDistributorByCode($distributorCode);

            //TSO Lookup
            $tso = $this->userRepository->findUserByCode($tsoCode);

            $this->tsoRepository->ensureNotAvailableByTSOAndDistributor($distributor->id, $tso->id);
            $this->tsoRepository->create([
                'upazila_id' => $distributor->id,
                'user_id' => $tso->id,
                'name' => $distributor->firstname ?? '',
                'bn_name' => $distributor->officeid
            ]);

        }
    }

    /**
     * TYPE = 22 → TSO/TSM-Distributor Un-Mapping Upload
     */
    private function uploadTSOUnMapping(array $rows)
    {
        foreach ($rows as $index => $row) {
            $distributorCode = trim($row[0]);
            $tsoCode = trim($row[1]);

            //Distributor Lookup
            $distributor = $this->userRepository->findDistributorByCode($distributorCode);

            //TSO Lookup
            $tso = $this->userRepository->findUserByCode($tsoCode);

            $this->tsoRepository->deleteByTSOAndDistributor($distributor->id, $tso->id);
        }
    }

    /**
     * TYPE = 23 → Tertiary Sales Delete Upload
     */
    private function uploadTertiarySaleDelete(array $rows)
    {
        foreach ($rows as $index => $row) {
            $imei = trim($row[0]);
            $this->tertiarySaleRepository->destroy($imei);
        }
    }

    /**
     * TYPE = 30 → Temporary Table IMEI Delete Upload
     */
    private function uploadTemporaryTableDataDelete(array $rows)
    {
        foreach ($rows as $index => $row) {
            $imei = trim($row[0]);
            $this->imeiUploadRepository->destroy($imei);
        }
    }

    /**
     * TYPE = 202 → Inactive Retail Upload
     */
    private function uploadInactiveRetail(array $rows)
    {
        foreach ($rows as $index => $row) {
            $retailerCode = trim($row[0]);

            $this->userRepository->findRetailerByCode($retailerCode)->update([
                'active' => 0
            ]);
        }
    }

    /**
     * TYPE = 204 → Replace Request Delete Upload
     */
    private function uploadReplaceRequestDelete(array $rows)
    {
        foreach ($rows as $index => $row) {
            $imei = trim($row[0]);
            $this->replaceRepository->destroy($imei);
        }
    }

    /**
     * TYPE = 205 → Stock Update for Specific Date Upload
     */
    private function uploadStockUpdate(array $rows)
    {
        foreach ($rows as $index => $row) {
            $imei = trim($row[0]);
            $date = DateHelper::parseCsvDate($row[4]);

            $this->stockRepository->findByIMEI($imei)->update([
                'created_at' => $date
            ]);
        }
    }

    /**
     * TYPE = 206 → Replaced IMEI Change Upload
     */
    private function uploadReplaceImeiChange(array $rows)
    {
        foreach ($rows as $index => $row) {
            $oldImei = trim($row[0]);
            $newImei = trim($row[1]);

            $this->tertiarySaleRepository->ensureNotAvailableByIMEI($newImei);
            $terSale = $this->tertiarySaleRepository->findByIMEI($oldImei);
            $stock = $this->stockRepository->findByIMEI($newImei);

            $terSale->update([
                'sno' => $stock->sno,
                'imei' => $stock->imei
            ]);

            DB::table('replace-changes')->insert([
                'old_imei' => $oldImei,
                'new_imei' => $newImei
            ]);

        }
    }

    /**
     * TYPE = 207 → Replaced data to Received Upload
     */
    private function uploadReplaceImeiReceive(array $rows)
    {
        foreach ($rows as $index => $row) {
            $replaceId = trim($row[0]);
            $this->replaceRepository->find($replaceId)->update([
                'service_status' => 'Pending',
                'received' => '',
                'void' => '',
                'delivery_date' => '',
            ]);

        }
    }

    /**
     * TYPE = 208 → IMEI Change Upload
     */
    private function uploadIMEIChange(array $rows)
    {
        foreach ($rows as $index => $row) {
            $oldImei1 = trim($row[0]);
            $oldImei2 = trim($row[1]);
            $newImei1 = trim($row[2]);
            $newImei2 = trim($row[3]);

            $this->stockRepository->findByBothIMEI($oldImei1, $oldImei2)->update([
                'sno' => $newImei1,
                'imei' => $newImei2
            ]);

           // Update primary sale (but ignore if not found)
            $priSale = $this->primarySaleRepository->findByBothIMEINullable($oldImei1, $oldImei2);
            if ($priSale) {
                $priSale->update([
                    'sno'  => $newImei1,
                    'imei' => $newImei2,
                ]);
            }
        }
    }

    /**
    * TYPE = 209 → Stock Product Change Upload
    */
    private function stockProductChange(array $rows)
    {
        foreach ($rows as $index => $row) {
            $imei = trim($row[0]);
            $newModel = $row[1];

            $product = $this->productRepository->findByModel($newModel);

            $this->stockRepository->findByIMEI($imei)->update([
                'product_id' => $product->id,
                'brand_id' => $product->brand_id,
            ]);
        }
    }

}
