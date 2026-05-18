<?php

namespace App\Http\Controllers;

use App\JobProgress;


class JobController extends Controller
{
    public function index()
    {
        $jobs = JobProgress::all();
        return view('jobs.show', compact('jobs'));
    }

    public function data()
    {
        $user = auth()->user();

        $jobsQuery = JobProgress::whereDate('created_at', today())
            ->orderBy('created_at', 'desc');

        if ($user->officeid !== 'SVND') {
            $jobsQuery->where('user_id', $user->id);
        }

        return response()->json($jobsQuery->get());
    }



    public function details($id)
    {
        $job = JobProgress::findOrFail($id);
        $logDetails = json_decode($job->log_details, true) ?? [];
        $modelErrors = json_decode($job->model_error, true) ?? [];
        $noStockList = json_decode($job->no_stock, true) ?? [];
        $soldList = json_decode($job->sold_list, true) ?? [];
        $tertiarySoldList = json_decode($job->tertiary_sold_list, true) ?? [];
        $noDealerList = json_decode($job->no_dealer, true) ?? [];

        return view('jobs.details', compact('job', 'logDetails', 'modelErrors', 'noStockList', 'soldList', 'tertiarySoldList', 'noDealerList'));
    }

    public function jobDetails($id)
    {
        $job = JobProgress::findOrFail($id);
        $logDetails = json_decode($job->log_details, true) ?? [];
        $modelErrors = json_decode($job->model_error, true) ?? [];
        $noStockList = json_decode($job->no_stock, true) ?? [];
        $soldList = json_decode($job->sold_list, true) ?? [];
        $tertiarySoldList = json_decode($job->tertiary_sold_list, true) ?? [];
        $noDealerList = json_decode($job->no_dealer, true) ?? [];

        return view('jobs.warehouse.details', compact('job', 'logDetails', 'modelErrors', 'noStockList', 'soldList', 'tertiarySoldList', 'noDealerList'));
    }

    public function warehouseJobs()
    {
        $jobs = JobProgress::all();
        return view('jobs.warehouse.index', compact('jobs'));
    }



}
