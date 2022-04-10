<?php
namespace App\Twig;

use NumberFormatter;
use phpDocumentor\Reflection\Types\Integer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CustomExtension extends AbstractExtension
{
    public function getFunctions()
        {
            return [
                new TwigFunction('date_difference', [$this, 'date_difference']),
                new TwigFunction('format_to_currency', [$this, 'format_to_currency']),

            ];
        }
    //diffrence of days
    public function date_difference(String $date,String $date1):int{
        return strtotime($date)- strtotime($date1);
    }
    //format to a currency
     public  function format_to_currency(float $number){
         $fmt = new \NumberFormatter( 'en', \NumberFormatter::CURRENCY );
         return $fmt->formatCurrency($number, "USD");
     }


}