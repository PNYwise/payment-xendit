<?php

use App\Http\Controllers\api\payment\XenditController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

route::get('/xendit/va/list', [XenditController::class, 'getListVA']);
route::post('/xendit/va/invoice', [XenditController::class, 'createVA']);
route::post('/xendit/va/callback', [XenditController::class, 'callbackVA']);


route::get('/xendit/disbursement/list', [XenditController::class, 'getListDisbursements']);
route::post('/xendit/disbursement/invoice', [XenditController::class, 'createDisbursement']);
route::post('/xendit/disbursement/callback', [XenditController::class, 'callbackDisbursement']);
