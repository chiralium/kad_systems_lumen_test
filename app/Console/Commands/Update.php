<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Currency;

class Update extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating the currency';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function get_xml( $path )
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path);
        curl_setopt($ch, CURLOPT_FAILONERROR,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $retValue = curl_exec($ch);
        curl_close($ch);
        return $retValue;
    }

    public function get_meta_data( $id )
    {
        $ref_data = $this->get_xml("http://www.cbr.ru/scripts/XML_valFull.asp");
        $ref_data = new \SimpleXMLElement( $ref_data );

        foreach ($ref_data as $ref) {
            if ( current($ref["ID"]) == $id ) return $ref;
        }
        return null;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Currency::truncate();
        $data = $this->get_xml("http://www.cbr.ru/scripts/XML_daily.asp");
        $data = new \SimpleXMLElement( $data );

        foreach ($data as $curr) {
            $new_curr = new Currency();

            $meta_data = $this->get_meta_data( current($curr["ID"]));

            $new_curr->name_rus = $meta_data->Name;
            $new_curr->name_eng = $meta_data->EngName;
            $new_curr->alpha_code = $meta_data->ISO_Char_Code;
            $new_curr->digit_code = $meta_data->ISO_Num_Code;
            $new_curr->rate = $curr->Value;

            $new_curr->save();
        }
    }
}
