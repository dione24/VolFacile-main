<?php
namespace App\Http\Controllers;

use App\Services\AviationStackService;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    protected $aviationStackService;

    public function __construct(AviationStackService $aviationStackService)
    {
        $this->aviationStackService = $aviationStackService;
    }

    public function showFlightDetails(Request $request, $flightNumber)
    {
        $flightDate = $request->input('flight_date', date('Y-m-d')); // Utilisez la date actuelle par défaut
        $flightDetails = $this->aviationStackService->getFlightDetails($flightNumber, $flightDate);

        return response()->json($flightDetails);
    }
}