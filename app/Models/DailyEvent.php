<?php

namespace App;

use App\Http\Controllers\FirebaseController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Self_;

class DailyEvent extends Model
{
  protected $redirectTo = '/daily-event';
  protected $fillable = ['activityApproved', 'activityDate', 'activityPreferred',
                          'address', 'city', 'content', 'createDate', 'eventCategory1',
                          'eventCategory2', 'eventCategory3', 'eventFeeCharged',
                          'eventMainSubType', 'featuredStatus', 'imgs', 'numLikes', 'numsRead', 'title', 'website', 'zip'];
  public $isApproved;
  public $eventDates;
  public $preferred;
  public $address;
  public $city;
  public $content;
  public $created;
  public $creatorName;
  public $creatorImageURL;
  public $creatorStatus;
  public $firstCategory;
  public $secondCategory;
  public $thirdCategory;
  public $fee;
  public $subtype;
  public $featured;
  public $images;
  public $singleImage;
  public $likes;
  public $reads;
  public $title;
  public $website;
  public $zip;

  private $serviceAccount;
  private $eid;

  /**
   * Create a new controller instance.
   *
   * @param array $de
   * @param $event_id
   */

  public function __construct(array $de, $event_id)
  {
    parent::__construct($de);
    $this->serviceAccount = new FirebaseController();
    $this->eid = $event_id;
    $this->isApproved = $de['activityApproved'];
    $this->eventDates =  $de['activityDate'];
    $this->preferred = (array_key_exists('activityPreferred', $de)) ? $de['activityPreferred'] : false;
    $this->address = $de['address'];
    $this->city = $de['city'];
    $this->content = $de['content'];
    $this->created = $de['createDate'];
    $this->creatorName = $de['creator']['userName'];
    $this->creatorImageURL = $de['creator']['userImg'];
    $this->creatorStatus = $de['creator']['userStatus'];
    $this->firstCategory = $de['eventCategory1'];
    $this->secondCategory = $de['eventCategory2'];
    $this->thirdCategory = $de['eventCategory3'];
    $this->fee = $de['eventFeeCharged'];
    $this->subtype = $de['eventMainSubType'];
    $this->featured = (array_key_exists('featuredStatus', $de)) ? $de['featuredStatus'] : false;
    $this->images = $de['imgs'] ?? [self::defaultDailyEventImage()];
    $this->likes = $de['numsLike'];
    $this->reads = $de['numsRead'];
    $this->title = $de['title'];
    $this->website = $de['website'];
    $this->zip = $de['zip'];
  }

  public function sayHello(){
    return 'Hello';
  }

  public static function defaultDailyEventImage() {
    return 'https://firebasestorage.googleapis.com/v0/b/momsinla-de26b.appspot.com/o/Daily_Events%2Fdefault.png?alt=media';
  }

  public function findDailyEventUserLike(){
//    dump($this->serviceAccount->returnUserDailyEventLike($this->eid));
    return $this->serviceAccount->returnUserDailyEventLike($this->eid);
    //dd($r['haveILiked']);
  }

  public function findDailyEventUserSave(){
//    dump($this->serviceAccount->returnUserDailyEventLike($this->eid));
    return $this->serviceAccount->returnUserDailyEventSave($this->eid);
  }

  public function convertCategoryOne(){
    return ((bool)$this->firstCategory === true) ? 'Free' : 'Fee';
  }

  public function convertCategoryTwo(){
    return ((bool)$this->secondCategory === true) ? 'Public' : 'Private';
  }

  public function convertCategoryThreeToLabels() {
    $label = '';

    for ($i = 0; $i < 8; $i++) {
      $c = $this->thirdCategory[$i];

      switch ($i) {
        case 0;
          ($c == true ? $label .= '<div style="background-color: var(--pearl-blue-opac)" class="p-2 text-white m-1">Indoor</div>' : '');
          break;
        case 1;
          ($c == true ? $label .= '<div style="background-color: var(--pearl-blue-opac)" class="p-2 text-white m-1">Educational</div>' : '');
          break;
        case 2;
          ($c == true ? $label .= '<div style="background-color: var(--pearl-blue-opac)" class="p-2 text-white m-1">Zoo Related</div>' : '');
          break;
        case 3;
          ($c == true ? $label .= '<div style="background-color: var(--pearl-blue-opac)" class="p-2 text-white m-1">Playground</div>' : '');
          break;
        case 4;
          ($c == true ? $label .= '<div style="background-color: var(--pearl-blue-opac)" class="p-2 text-white m-1">Outdoor</div>' : '');
          break;
        case 5;
          ($c == true ? $label .= '<div style="background-color: var(--pearl-blue-opac)" class="p-2 text-white m-1">Recreational</div>' : '');
          break;
        case 6;
          ($c == true ? $label .= '<div style="background-color: var(--pearl-blue-opac)" class="p-2 text-white m-1">Free Parking</div>' : '');
          break;
        case 7;
          ($c == true ? $label .= '<div style="background-color: var(--pearl-blue-opac)" class="p-2 text-white m-1">Other</div>' : '');
          break;
      }
    }
    echo $label;
  }

  public function slugTitle(){
    return str_replace(' ', '-', $this->title);
  }

  public function smallEventDescription(){
    return Str::limit($this->content, 600);
  }

  public function minimalEventDescription(){
    return Str::limit($this->content, 125);
  }

  public function getEventComments(){
    return $this->serviceAccount->findOrFailyDailyEventComments($this->eid);
  }

  function custom_number_format($precision = 3) {
    if ($this->reads < 1000000) {
      // Anything less than a million
      $n_format = number_format($this->reads);
    } else if ($this->reads < 1000000000) {
      // Anything less than a billion
      $n_format = number_format($this->reads / 1000000, $precision) . 'M';
    } else {
      // At least a billion
      $n_format = number_format($this->reads / 1000000000, $precision) . 'B';
    }

    return $n_format;
  }

  function number_shorten( $precision = 2, $divisors = null) {

    // Setup default $divisors if not provided
    if (!isset($divisors)) {
      $divisors = array(
        pow(1000, 0) => '', // 1000^0 == 1
        pow(1000, 1) => 'K', // Thousand
        pow(1000, 2) => 'M', // Million
        pow(1000, 3) => 'B', // Billion
        pow(1000, 4) => 'T', // Trillion
        pow(1000, 5) => 'Qa', // Quadrillion
        pow(1000, 6) => 'Qi', // Quintillion
      );
    }

    // Loop through each $divisor and find the
    // lowest amount that matches
    foreach ($divisors as $divisor => $shorthand) {
      if (abs($this->reads) < ($divisor * 1000)) {
        // We found a match!
        break;
      }

    }

    // We found our match, or there were no matches.
    // Either way, use the last defined value for $divisor.

    if ($shorthand === '')
      return $this->reads;
    else
      return number_format($this->reads / $divisor, $precision) . $shorthand;

  }


}
