<?php

namespace App\Http\Controllers;

use App\DailyEvent;
use Faker\Generator as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class DailyEventsController extends Controller
{
    private $serviceAccount;
    private $categoryThreeArr = ['Indoor', 'Educational', 'Zoo Related', 'Playground', 'Outdoor', 'Recreational', 'Free Parking', 'Other'];

    public function __construct()
    {
        $this->serviceAccount = new FirebaseController();
        $this->middleware('auth')->except(['index', 'show', 'fallbackShow', 'updateReadCount']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Should load preferred events!
        $eventList = $this->serviceAccount->returnApprovedDailyEventList();

//      @dump($eventList);

//      @dump('First Key', key($eventList));

//      end($eventList);

//      @dump('Last Key', key($eventList));
        dd($eventList);
        //   return view('dailyevent.home', compact('eventList'));
    }

    public function listEventCollection()
    {
        //
        return view('layouts.dailyevent.list');
    }

    public function manageOfficialEvents()
    {
        /* Remove the session info */
        session()->forget(['pages.page', 'pages.next', 'pages.prev']);

        $official_e = $this->serviceAccount->allOfficialEvents();
        return view('admin.management.dailyevents.officialEvents', ['official' => $official_e, 'page' => 0]);
    }

    public function manageSelfPostedEvents()
    {
        $self_e = $this->serviceAccount->allSelfPostedEvents();
        return view('admin.management.dailyevents.selfPostedEvents', ['self' => $self_e]);
    }

    public function manageComments()
    {
        /* Remove the session info */
        session()->forget(['pages.page', 'pages.next', 'pages.prev']);

        $comments = $this->serviceAccount->getAllDailyEventComments();
        end($comments);
        $forward_key = key($comments);
        array_pop($comments);
        return view('admin.management.dailyevents.commentsDailyEvents', ['comments' => $comments, 'page' => 0, 'forward_key' => $forward_key]);
    }

    public function deleteEvent(Request $request)
    {

        $_file_with_reference = null;
        $_image_names_arr = [];

        if (strpos($request->get('_images')[0], 'amazonaws.com') !== false) {
            $_images = $request->get('_images');

            foreach ($_images as $i) {
                $split = explode('/', $i);
                $image_key = $split[4] . '/' . $split[5];
                Storage::disk('s3')->delete($image_key);
            }

        }

        if (strpos($request->get('_images')[0], 'firebasestorage.googleapis.com') !== false) {
            $_images = $request->get('_images');

            foreach ($_images as $i) {
                $_file_with_reference = substr(explode('/', $i)[7], 15);
                $_alt_media = "?alt=media";
                $_filename = substr($_file_with_reference, 0, (strlen($_file_with_reference) - strlen($_alt_media)));
                array_push($_image_names_arr, $_filename);
            }

        }

        $this->serviceAccount->deleteDailyEventAndComments($request->get('eid'));
        return response()->json(['S' => 'success', 'I' => $_image_names_arr]);
    }

    public function deleteParentComment($eid, $pid)
    {
        $this->serviceAccount->deleteDailyEventParentComment($eid, $pid);
    }

    public function deleteChildComment($eid, $pid, $chid)
    {
        $this->serviceAccount->deleteDailyEventChildComment($eid, $pid, $chid);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Faker $faker
     * @return \Illuminate\Http\Response
     */
    public function create(Faker $faker)
    {
        //
        return view('forms._dailyevent', compact('faker'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* Validate the request information before storing */
        $request->validate([
            'title' => 'bail|required|max:75',
            'fee' => 'required',
            'description' => 'required',
            'startTime.*' => 'required',
            'endTime.*' => 'required',
            'street' => 'required|max:100',
            'city' => 'required|max:50',
            'zip' => 'required|max:5',
            'url' => 'required',
        ]);

        $requestCat3 = $request->get('category-3');
        /* Convert request Category 3 */
        $convertedCat3 = [];
        (in_array('Indoor', $requestCat3) ? $convertedCat3[0] = true : $convertedCat3[0] = false);
        (in_array('Educational', $requestCat3) ? $convertedCat3[1] = true : $convertedCat3[1] = false);
        (in_array('Zoo Related', $requestCat3) ? $convertedCat3[2] = true : $convertedCat3[2] = false);
        (in_array('Playground', $requestCat3) ? $convertedCat3[3] = true : $convertedCat3[3] = false);
        (in_array('Outdoor', $requestCat3) ? $convertedCat3[4] = true : $convertedCat3[4] = false);
        (in_array('Recreational', $requestCat3) ? $convertedCat3[5] = true : $convertedCat3[5] = false);
        (in_array('Free Parking', $requestCat3) ? $convertedCat3[6] = true : $convertedCat3[6] = false);
        (in_array('Other', $requestCat3) ? $convertedCat3[7] = true : $convertedCat3[7] = false);

        /* Convert times into time array */
        $timesConverted = [];
        foreach ($request->get('startTime') as $key => $t) {
//        $timestamp = $carbon_obj = Carbon::createFromFormat('Y-m-d H:i' , $t,'America/Chicago')->timestamp;
            array_push($timesConverted, ['from' => (integer) $request->get('convertedStartTime')[$key], 'to' => (integer) $request->get('convertedEndTime')[$key]]);
        }

        /* Build Image URLs */
        $images = [];

        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $p) {
                $filename = \Auth::user()->uid . round(microtime(true) * 1000) . $p->getClientOriginalName();
                $path = public_path('/daily/') . $filename;

                /* Resize to fixed max width size */
                $img = Image::make($p)->resize(2000, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->stream('jpg', 100);

//          Storage::put('public/daily/'.$filename, $img);
//          $uploadPhotoURL = url('storage/daily').'/'.$filename;
//          Storage::disk('production')->put('daily/' . $filename, $img);
//          $uploadPhotoURL = url('_bucket/daily') . '/' . $filename;
//          array_push($images, $uploadPhotoURL);

                Storage::disk('s3')->put('daily/' . $filename, $img);
                # S3 bucket URL
                $uploadPhotoURL = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/daily/' . $filename;

                array_push($images, $uploadPhotoURL);

            }

            /* Create the event object and push to firebase, default image if none uplaoded */
            $this->serviceAccount->insertDailyEvent($request, $convertedCat3, $timesConverted, $images);

        } else {

            $this->serviceAccount->insertDailyEvent($request, $convertedCat3, $timesConverted, $images);
        }

        return redirect()->route('de.home');
    }

    private function unslugTitle($t)
    {
        return str_replace('-', ' ', $t);
    }

    /**
     * Display the specified resource.
     *
     * @param $title
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $title)
    {
        //$id is to convert array to object via model
        $stripped_title = str_replace('-', ' ', $title);
        $event_info = $this->serviceAccount->findOrFailDailyEvent($id, $stripped_title);
//      dd($event_info);
        return view('dailyevent.detailed', ['details' => new DailyEvent($event_info, $id), 'id' => $id]);
    }

    public function fallbackShow($id)
    {
        $event_info = $this->serviceAccount->findOrFailDailyEventFallback($id);
        if (is_null($event_info)) {
            return abort(404);
        } else {
            return view('dailyevent.detailed', ['details' => new DailyEvent((array) $event_info, $id), 'id' => $id]);
        }

    }

    public function editDailyEvent($eid, $title)
    {
        //Get the event details
        $edit_details = $this->serviceAccount->findOrFailDailyEvent($eid, $this->unslugTitle($title));
        return view('forms._edit-dailyevent', ['eid' => $eid, 'details' => new DailyEvent($edit_details, $eid)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param $original
     * @return void
     */
    public function update(Request $request, $id)
    {
        // Parse and Convert the original event details to object
        $original = json_decode($request->get('original'));

        /* Convert request Category 3 */
        $convertedCat3 = [];
        $rc3 = $request->get('category-3');
        (in_array('Indoor', $rc3) ? $convertedCat3[0] = true : $convertedCat3[0] = false);
        (in_array('Educational', $rc3) ? $convertedCat3[1] = true : $convertedCat3[1] = false);
        (in_array('Zoo Related', $rc3) ? $convertedCat3[2] = true : $convertedCat3[2] = false);
        (in_array('Playground', $rc3) ? $convertedCat3[3] = true : $convertedCat3[3] = false);
        (in_array('Outdoor', $rc3) ? $convertedCat3[4] = true : $convertedCat3[4] = false);
        (in_array('Recreational', $rc3) ? $convertedCat3[5] = true : $convertedCat3[5] = false);
        (in_array('Free Parking', $rc3) ? $convertedCat3[6] = true : $convertedCat3[6] = false);
        (in_array('Other', $rc3) ? $convertedCat3[7] = true : $convertedCat3[7] = false);

        /* convertedStartTime convertedEndTime */
        /* Original event times */
        if (is_null($request->input('convertedStartTime.0')) || is_null($request->input('convertedEndTime.0'))) {
            $org_e_times = $original->activityDate;
        } else {
            $org_e_times[0] = ['from' => $request->input('convertedStartTime.0'), 'to' => $request->input('convertedEndTime.0')];
        }

        /* Check for images if any uploaded, add procedure field of applicable, remove old image, upload new */
        $images = [];

        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $p) {
                $filename = \Auth::user()->uid . round(microtime(true) * 1000) . $p->getClientOriginalName();

                /* Resize to fixed max width size */
                $img = Image::make($p)->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->stream('jpg', 90);

//          Storage::put('public/daily/' . $filename, $img);
//          $uploadPhotoURL = url('storage/daily') . '/' . $filename;

                //Storage::disk('production')->put('daily/' . $filename, $img);
                //$uploadPhotoURL = url('_bucket/daily') . '/' . $filename;
                Storage::disk('s3')->put('daily/' . $filename, $img);
                # S3 bucket URL
                $uploadPhotoURL = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/daily/' . $filename;

                array_push($images, $uploadPhotoURL);
            }
        } else {
            /* Keep original images */

        }

        $this->serviceAccount->updateDailyEvent($id, $original, $request, $convertedCat3, $org_e_times, $images);

        return redirect()->route('de.m.o')->with('success', "The event has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function onDailyEventLike(Request $request, $eid)
    {
        $updatedLikes = null;
        $this->serviceAccount->addLikeToUserNode($eid, filter_var($request->get('state'), FILTER_VALIDATE_BOOLEAN));

        switch (filter_var($request->get('state'), FILTER_VALIDATE_BOOLEAN)) {
            case null:
            case false:
                if ((int) $request->get('prevLikes') > 0) {
                    $updatedLikes .= (int) $request->get('prevLikes') + 1;
                }

                break;
            case true:
                $updatedLikes .= (int) $request->get('prevLikes') - 1;
                break;
            default:

        }
//      $this->serviceAccount->updateEventLikeCount($eid, $updatedLikes);
        return response()->json(['event' => $eid, 'r' => $request->get('state'), 'l' => (int) $updatedLikes]);
    }

    public function onDailyEventSave(Request $request, $eid)
    {
        $this->serviceAccount->addSaveToUserNode($eid, filter_var($request->get('state'), FILTER_VALIDATE_BOOLEAN));
        return response()->json(['event' => $eid, 'r' => $request->get('state')]);
    }

    public function updateReadCount(Request $request, $eid)
    {
        $updated = (int) $request->get('prev') + 1;
        $this->serviceAccount->updateReadCount($eid, $updated);
//      return response()->json(['r' => $updated, 'reads' => 'Something returned']);
    }

    public function insertNewParentComment(Request $request, $eid, $title)
    {

        $request->validate([
            'reply' => 'required|max:1000',
        ]);

        $this->serviceAccount->insertNewDailyEventParentComment($eid, $title, $request);
        return redirect()->back();
    }

    public function insertNewChildComment(Request $request, $eid, $par_com_id)
    {

        $request->validate([
            'reply' => 'required|max:1000',
        ]);
        $this->serviceAccount->insertNewDailyEventChildComment($eid, $par_com_id, $request);
        return redirect()->back();
    }

    public function updateFeaturedStatus(Request $request)
    {
        /*Event ID, Current Value */
        $this->serviceAccount->onUpdateFeaturedStatus($request->get('eid'), filter_var($request->get('state'), FILTER_VALIDATE_BOOLEAN));
        return response()->json(['eid' => $request->get('eid'), 'updated_state' => !filter_var($request->get('state'), FILTER_VALIDATE_BOOLEAN)]);
//    return response()->json(['eid' => $request->get('eid'), 'state' => $request->get('state') ]);
    }

    public function updateApprovedStatus(Request $request)
    {
        $this->serviceAccount->onUpdateApprovedStatus($request->get('eid'));
    }

}