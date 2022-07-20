<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordEmail;
use App\Models\ForgotPasswordReset;
use App\Models\StandardSignup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StandardSignupController extends Controller
{
    private $s3BaseClientUL = "https://momsinla-storage.s3.us-west-1.amazonaws.com";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Clean input before storing in database
        // Encrypt Password
        $user_uuid = Str::uuid()->toString();
        $email = $request->email;
        $zip = $request->zip;
        $name = $request->name;
        $password = Hash::make($request->password);
        $phone = $request->phone;
        $uuid = $user_uuid;
        $profile_image_src = "https://momsinla-storage.s3.us-west-1.amazonaws.com/partybemine/profile/default.png";
        $token = Str::random(60);
        $domain = 'http://tracker.technologycredit.io/api/partybemine/user/' . $user_uuid;
        $role = 'USER';

        // Returns TRUE is exists FALSE if available
        $exits = StandardSignup::where('email', '=', $email)->exists();

        // Create user
        if (!$exits) {
            // Misc
            // Do not add spaces to the card otherwise it wont read when generated
            $VCARD = "BEGIN:VCARD
N:$name;
EMAIL:$email
URL:$domain
UID:$uuid
ROLE:$role
KIND:Application
VERSION:3.0
END:VCARD";

            // QrCode::size(500)->format('png')->generate($VCARD, public_path('/storage/' . $uuid . '.png'));
            $qr_code = QrCode::size(500)->format('png')->generate($VCARD);
            $output_file = "/partybemine/user_qr_codes/" . $uuid . '.png';

            // Save file
            Storage::disk('s3')->put($output_file, $qr_code);

            $user = StandardSignup::create([
                'role' => $role,
                'email' => $email,
                'zip' => $zip,
                'name' => $name,
                'password' => $password,
                'uuid' => $uuid,
                'qrcode_src' => $this->s3BaseClientUL . $output_file,
                'profile_image_src' => $profile_image_src,
                'token' => $token,
                'phone' => $request->phone,
            ]);

            return response()->json($user, 201);

        } else {
            return response()->json('Email is taken.', 200);
        }

    }

    public function quickLogin(Request $request)
    {
        // Search user bby ID
        // Chheck if email, password and token match
        // IF Yes, Login ELSE send error
        // Check if the record exists
        $record = StandardSignup::select('id')->where('id', $request->id)->exists();
        if ($record) {
            // $user = StandardSignup::find(48)->makeVisible(['password']);
            $user = StandardSignup::find($request->id);
            $flag = (strtolower($request->email) === strtolower($user->email) && $request->token === $user->token) ? true : false;
            if ($flag) {
                return response()->json($user, 200);
            }
            return response()->json('Details do not match', 400);
        }
        return response()->json('No record found!', 400);
    }

    public function login(Request $request)
    {
        // Search user bby ID
        // Chheck if email, password and token match
        // IF Yes, Login ELSE send error
        // Check if the record exists
        $record = StandardSignup::select('email')->where('email', '=', $request->email)->exists();
        if ($record) {
            $user = StandardSignup::where('email', '=', $request->email)->get()->first()->makeVisible(['password']);
            $flag = (strtolower($request->email) === strtolower($user->email) && Hash::check($request->password, $user->password)) ? true : false;
            if ($flag) {
                return response()->json($user, 200);
            }
            return response()->json('Details do not match', 400);
        }
        return response()->json('No record found!', 400);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StandardSignup  $standardSignup
     * @return \Illuminate\Http\Response
     */
    public function show(StandardSignup $standardSignup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StandardSignup  $standardSignup
     * @return \Illuminate\Http\Response
     */
    public function edit(StandardSignup $standardSignup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StandardSignup  $standardSignup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StandardSignup $standardSignup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StandardSignup  $standardSignup
     * @return \Illuminate\Http\Response
     */
    public function destroy(StandardSignup $standardSignup)
    {
        //
    }

    public function checkIfGoogleAccountIsStored(Request $request)
    {
        $record = StandardSignup::where('uuid', '=', $request->sub)->get();
        $role = 'USER';

        // IF no record create it and send it back
        if (!count($record)) {
            $domain = 'http://tracker.technologycredit.io/api/partybemine/user/' . $request->sub;

            $GOOGLE_VCARD = "BEGIN:VCARD
N:$request->name;
EMAIL:$request->email
URL:$domain
UID:$request->sub
ROLE:$role
KIND:Application
VERSION:3.0
END:VCARD";

            $qr_code = QrCode::size(500)->format('png')->generate($GOOGLE_VCARD);
            $output_file = "/partybemine/user_qr_codes/" . $request->sub . '.png';

            // Save file
            Storage::disk('s3')->put($output_file, $qr_code);

            $user = StandardSignup::create([
                'role' => 'USER',
                'email' => $request->email,
                'zip' => $request->zip,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'uuid' => $request->uuidsub,
                'qrcode_src' => $this->s3BaseClientUL . $output_file,
                'profile_image_src' => $request->profile_image_src,
                'token' => Str::random(60),
                'uuid' => $request->sub,
            ]);
            return response()->json($user, 201);
        }
        // Account already exist so return user object
        if (strtolower($request->email) === strtolower($record->first()->email) && $request->sub === $record->first()->uuid) {
            return response()->json($record->first(), 200);
        }

        return response()->json(["error" => "Could not authenticate"], 201);
    }

    /**
     * @param $img
     * @param $username
     * @return array
     */
    private function returnDecodedImageAttributes($img)
    {
        $image = $img;
        $imageName = 'image_' . Str::random(60) . '.png'; //generating unique file name;
        $decodedBase64 = base64_decode($image);
        // Store locally for the meantime
        $output = "/partybemine/event_images/" . $imageName;
        $img = Image::make($decodedBase64)->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        })->stream('jpeg', 90);

        Storage::disk('s3')->put($output, $img);
        // Storage::disk('s3')->put($output, $decodedBase64);
        return $this->s3BaseClientUL . $output;
    }

    public function updateProfileDetails(Request $request)
    {
        $user = StandardSignup::where('id', '=', $request->id)->get()->first();

        if ($request->image !== null) {
            // TODO DO NOT UPDATE GOOGLE EMAILS OR ELSE A LOT NEEDS TO BE CHANGED!
            if (!is_numeric($user->uuid)) {
                $user->email = $request->email;
            }
            $user->zip = $request->zip;
            $user->phone = $request->phone;
            $user->profile_image_src = $this->returnDecodedImageAttributes($request->image);
            $user->save();
            // Update image
            return response()->json($user, 200);
        } else {
            // Update other fields
            if (!is_numeric($user->uuid)) {
                $user->email = $request->email;
            }
            $user->zip = $request->zip;
            $user->phone = $request->phone;
            $user->save();

            return response()->json($user, 200);
        }

    }

    public function sendForgotPasswordEmail(Request $request)
    {
        // Search user based on email / id
        $user = StandardSignup::where('email', '=', $request->email)->get()->first();

        // Return error if no match
        // Return error if account is Google type
        if ($user) {
            // code
            $six_digit_random_number = random_int(100000, 999999);
            // Check if not GOOGLE
            if (!is_numeric($user->uuid)) {
                // Proceed and send email if valid
                // Create forgot password record
                ForgotPasswordReset::create([
                    'code' => $six_digit_random_number,
                    'email' => $user->email,
                ]);
                //
                // TODO make controller for saving password reset codes to use when updating password
                $data = ['message' => 'This is a test!', 'code' => $six_digit_random_number];
                Mail::to('samexplains@protonmail.com')->send(new ForgotPasswordEmail($data));
                return response()->json(['success' => 'check your email'], 201);
            } else {
                return response()->json(['error' => 'Google accounts not allowed'], 201);

            }

        } else {
            return response()->json(['error' => 'no match found'], 201);
        }

    }

    public function updateUserPassword(Request $request)
    {
        // Grab last record for request email
        // Check if codes match
        $fpr = ForgotPasswordReset::where('email', '=', $request->email)->get()->last();
        $user = StandardSignup::where('email', '=', $request->email)->get()->first();

        if ($fpr) {
            if ($request->code == $fpr->code && $fpr->email === $user->email && !is_numeric($user->uuid)) {
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json(['success' => 'Information updated'], 201);
            } else {
                return response()->json(['error' => 'Information does not match!'], 201);
            }
        } else {
            return response()->json(['error' => 'Invalid informaton'], 201);
        }

    }

}