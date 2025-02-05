<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


class ReservationController extends Controller
{
    // index function to get all reservations
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json($reservations, 200);
    }
    // function to store reservation
    public function store(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'timeSlot' => 'required',
            'dateReservation' => 'required',
            'pc' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $reservation = Reservation::create($input);
        return response()->json($reservation, 200);
    }
    // show reservation by id
    public function show($id)
    {
        $reservation = Reservation::find($id);
        if (is_null($reservation)) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }
        return response()->json($reservation, 200);
    }
    // update reservation
    public function update(Request $request, Reservation $reservation)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required',
            'timeSlot' => 'required',
            'dateReservation' => 'required',
            'pc' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $reservation->user_id = $input['user_id'];
        $reservation->timeSlot = $input['timeSlot'];
        $reservation->dateReservation = $input['dateReservation'];
        $reservation->pc = $input['pc'];
        $reservation->save();
        return response()->json($reservation, 200);
    }
    // delete reservation
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return response()->json(['message' => 'Reservation deleted'], 200);
    }
    // get reservations by user id and check if user is a student
    public function getReservationsByUser($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if ($user->role == 'student') {
            $reservations = Reservation::where('user_id', $id)->get();
            return response()->json($reservations, 200);
        } else {
            return response()->json(['error' => 'User is not a student'], 401);
        }
    }
    // get pcs that not reserved
    public function getAvailablePcs($date, $timeSlot)
    {
        $reservations = Reservation::where('dateReservation', $date)->where('timeSlot', $timeSlot)->get();
        // list of 5 pcs
        $pcs = ['pc1', 'pc2', 'pc3', 'pc4', 'pc5'];
        // if $date and $timeSlot are null, return all pcs
        if (is_null($date) || is_null($timeSlot)) {
            return response()->json($pcs, 200);
        }

        foreach ($reservations as $reservation) {
            $key = array_search($reservation->pc, $pcs);
            if ($key !== false) {
                unset($pcs[$key]);
            }
        }
        // convert $pcs to array
        $pcs = array_values($pcs);
        return response()->json($pcs, 200);
    }
    // generate qr code for reservation
    public function generateQrCode($id)
    {
        $reservation = Reservation::find($id);
        if (is_null($reservation)) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        $qrCode = QrCode::create($reservation)
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $qrCodeData = $writer->write($qrCode)->getString();

        $qrCodeBase64 = base64_encode($qrCodeData);

        return response()->json(['qrCode' => $qrCodeBase64], 200);
    }

    // get user by reservation id
    public function getUserByReservation($id)
    {
        $reservation = Reservation::find($id);
        if (is_null($reservation)) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }
        $user = User::find($reservation->user_id);
        return response()->json($user, 200);
    }
    // get the number of reservations by user id
    public function getNumberOfReservationsByUser($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['success' => 0, 'message' => 'User not found'], 404);
        }
        if ($user->role == 'student') {
            $reservations = Reservation::where('user_id', $id)->get();
            $numberOfReservations = count($reservations);
            return response()->json(['success' => 1, 'numberOfReservations' => $numberOfReservations], 200);
        } else {
            return response()->json(['success' => 0, 'message' => 'User is not a student'], 401);
        }
    }
    // the user can have one reservation per time slot, get time slot available for user
    public function getTimeSlotAvailableForUser($id, $date)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if ($user->role == 'student') {
            $reservations = Reservation::where('user_id', $id)->where('dateReservation', $date)->get();
            $timeSlots = ['0', '1', '2', '3',];
            foreach ($reservations as $reservation) {
                $key = array_search($reservation->timeSlot, $timeSlots);
                if ($key !== false) {
                    unset($timeSlots[$key]);
                }
            }
            $timeSlots = array_values($timeSlots);
            return response()->json($timeSlots, 200);
        } else {
            return response()->json(['error' => 'User is not a student'], 401);
        }
    }

}
