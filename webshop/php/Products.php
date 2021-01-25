<?php

/** 
 * Klassen Products innehåller funktioner som:
 * hämtar API (JSON), omvandlar till en array och skriver ut produkter utifrån vald kategori
 * */

class Products
{

  public static $url = "http://localhost/webshop/api/";

  public static $legal_notice = "Genom att klicka på \"Boka\" bekräftar jag att jag har läst och godkänt <a href='#' class='alert-link text-primary'>Allmänna villkor</a>, <a href='#' class='alert-link text-primary'>Dataskyddsinformation</a> och <a href='#' class='alert-link text-primary'>Cookiepolicy</a>";

  public static function main()
  {
    try {
      $array = self::getData(self::$url);
      self::viewData($array);
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  public static function getData($url)
  {
    $json = @file_get_contents($url); // @ anger att eget felmeddelande ska visas
    if (!$json)
      throw new Exception("<div class='alert alert-danger'>Cannot access $url</div>");
    return json_decode($json, true); //True ger associativ array
  }

  /** 
   * Del 3:
   * Visa antal produkter via GET-Request
   * t.ex. http://localhost/webshop/?show=5
   * 
   * Säkerhetsoptimera API:et genom att validera all data som skickas till serven!
   * Visa ett lämpligt meddelande i JSON-format vid fel.
   * Bra exempel finns här: http://api.namnapi.se/v2/names.json?limit=0
   * **/

  public static function valid_num_input($input)
  {
    $min = 1; // Kan det här vara någonting annat än 1? Om arrayen är tom kanske
    $max = 10; // Det här borde vara COUNT av array, förutsatt att det är möjligt att göra så. TESTA
    $valid = false;

    if ($input >= $min and $input <= $max) {
      $valid = true;
    } else {
      throw new Exception("<div class='row justify-content-md-center'>
        <div class='col-auto alert alert-dismissible alert-warning'>
          <h5 class='alert-heading'>Ogiltigt värde</h5>
          <p>Ett ogiltigt värde har skrivits in i sidans URL. Begränsa visning av antal artiklar genom att ange tal från $min till $max.</p>
          <a href='webshop.php'><button type='button' class='btn-secondary btn'>Ladda om sidan</button></a>
        </div>
       </div>");
    }

      // För att visa felmeddelande i JSON, handlar det kanske om att inkludera felmeddelande från API? Alltså att lägga på en till nivå till arrayen, så att det blir "message" och "status"?

    return $valid;
  }

  public static function viewData($array)
  {

    // Visa antal utifrån giltigt värde, annars visas alla = 10 st.
    // Om ogiltigt värde matas in skickas Exception i html-format från valid_num_input()

    if (isset($_GET['show']) and self::valid_num_input($_GET['show'])) {
      $show = $_GET['show'];
    } else $show = 10;

    $shown = 0;

    $booking_form = "
        <div class='mt-5'>
        <form>

          <div class='form-row align-items-end'>
            <div class='col-lg-4'>
              <div class='input-group mb-2'>
                <div class='input-group-prepend'>
                  <div class='input-group-text'><i class='fas fa-user' style='font-size: 1.5rem;'></i></div>
                </div>
                <select id='inputChildren' class='form-control form-control-lg'>
                  <option selected>Vuxna</option>
                  <option>1 vuxen</option>
                  <option>2 vuxna</option>
                  <option>3 vuxna</option>
                  <option>4 vuxna</option>
                </select>
              </div>
            </div>

            <div class='col-lg-4'>
              <div class='input-group mb-2'>
                <div class='input-group-prepend'>
                  <div class='input-group-text'><i class='fas fa-child' style='font-size: 1.5rem;'></i></div>
                </div>
                <select id='inputChildren' class='form-control form-control-lg'>
                  <option selected>Barn</option>
                  <option>1 barn</option>
                  <option>2 barn</option>
                  <option>3 barn</option>
                  <option>4 barn</option>
                </select>
              </div>
            </div>

            <div class='col-lg-4'>
              <button type='submit' class='btn btn-lg btn-success mb-2 btn-block'>Boka</button>
            </div>

            <div class='col-12'>
            <p class='card-text small'> " . self::$legal_notice . "
      </div>
          </div>
        </form>
      </div><!-- ./form div -->";

    $product = "
        <div class='row'>";

    foreach ($array as $key => $value) {

      if ($shown < $show) {
        $inStock = rand(0, 50);

        $product .= "
        <div class='col-md-6 mb-4'>
          <div class='card h-100'>
            <div>
              <div class='icon-float right'>
                <i class='fas fa-asterisk text-white' style='font-size: 2rem;'></i>
              </div><!-- ./ icon -->
                                    
              <img src='$value[image]' alt='$value[image]' style='' class='card-img-top img-fluid'>
            </div><!-- ./ img -->

            <div class='card-body'>
              <div class='row'>
                <div class='col'>
                  <h2 class='card-title'><a href='#'>$value[name]</a></h2>
                </div>

                <div class='col-lg-auto'>
                  <h4 class='text-lg-right'>$value[price] SEK</h4>
                </div>
              </div>

              <p class='card-text text-right'>Lediga platser: $inStock</p>
              <p class='card-text mt-3'>$value[description]</p>

              $booking_form

            </div> <!-- ./ card-body -->
          </div> <!-- ./ card -->
        </div> <!-- ./ col -->";

        $shown++;
      } else {
        break;
      }
    }

    $product .= "</div>";

    echo $product;
  }
}
