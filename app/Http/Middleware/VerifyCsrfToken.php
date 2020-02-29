<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        "*",
        "/mytest",
        "/job/store",
        "/aa/{company}",
        "/jobs/{cid}",
        "/job-detail/{cid}/{id}",
        "/jobtypes",
        "/storeJob/{jobtype}/{cid}",
        "/update",
        "/delete",
        "/filter/{jobType}/{jobName}/{cid}/{startDate}/{endDate}",
        "/candiStatus/update/{id}/{candiStatus}",
        "/{companyName}/{candiId}/{jobId}/{randomNumber}",
        "/addTime/{cid}",
        "/interview_status",
        "/showTime/{cid}/{time}/{date}/{job_name}",
        "/show_intime_candi/{cid}/{time}/{date}/{job_name}/{flag}",
        "/update_time/{candicount}",
        "/delete_time/{candicount}",
        "/applyTimeInterview/{candiCountsId}/{candiId}",
        "/update_candi_time/{oldCandiCountId}/{newCandiCountId}/{candId}",
        "/cancel_candi_time/{oldCandiCountId}/{candId}",
        "/showCandiTime/{candiId}/{jobId}/{CID}{flag}",
        "/login",
        "/interview_status",
        "/entermail",
        "/jobtypes",
        "/ResetPassword",

    ];
}
