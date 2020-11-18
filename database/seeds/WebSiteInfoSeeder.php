<?php

use Illuminate\Database\Seeder;

use App\WebSiteInfo;

class WebSiteInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $webSiteInfo = new WebSiteInfo();
        $webSiteInfo->photo_title = 'BAZAR ALSEEB';
        $webSiteInfo->photo_desc = 'Grand Shopping Mall In Seeb,Oman';
        $webSiteInfo->web_desc = 'Seeb Grand Bazaar is an integrated and distinctive family shopping market built on the latest urban construction from inside and outside, and the shops have been distributed in a distinctive manner, where there are large spaces for visitors to pass between the shops, which gives them the opportunity to follow all the exhibits. The shops include a variety of activities such as clothes, perfumes, and accessories, as well as the presence of various restaurants, and the project has parking spaces with a capacity of more than 300 places serving the visitors. The Board of Directors of Al-Seeb Club believes that this project will add economic and commercial prosperity to the state of Al-Seeb, and a marketing destination for the state.';
        $webSiteInfo->save();
    }
}
