<?php

namespace App\Http\Controllers;

// // use Kreait\Firebase\ServiceAccount;
// use Kreait\Firebase;

class FirebaseController extends Controller
{
    private $database;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function index()
    {
        $database = $this->firebase->getDatabase()->getReference('DailyEvents/-LZcUVbgVpzFGIPvMT87')->getSnapshot();
        $value = $database->getValue();
    }

    /**************************************************/
    /* * Daily Events *********************************/
    /**************************************************/

    public function dailyEventsList()
    {
        return $this->database->getReference('DailyEvents')->getValue();
    }

    public function findOrFailDailyEvent(string $id, string $title)
    {
        return $this->firebase->getDatabase()->getReference('DailyEvents')->orderByChild('title')->equalTo($title)->getSnapshot()->getValue()[$id];
    }

    public function findOrFailDailyEventFallback(string $id)
    {
//    return $this->firebase->getDatabase()->getReference('DailyEvents')->orderByValue()->equalTo($id)->getSnapshot()->getValue()[$id];
        return $this->firebase->getDatabase()->getReference('DailyEvents/' . $id)->getSnapshot()->getValue();
    }

    public function returnApprovedDailyEventList()
    {
        return $this->firebase->getDatabase()->getReference('DailyEvents')->orderByChild('activityApproved')->equalTo(true)->limitToFirst(10)->getValue();
    }

    public function allOfficialEvents()
    {
//    return $this->firebase->getDatabase()->getReference('DailyEvents')->orderByChild('eventMainSubType')->equalTo('Official Event')->limitToFirst(2)->getValue();
        return $this->firebase->getDatabase()->getReference('DailyEvents')->orderByKey()->limitToFirst(10)->getValue();
    }
}