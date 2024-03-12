<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $country_data = [
            [
                "name" => "Afghanistan",
                "code" => "AFG",
                "dial_code" => "93",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Aland Islands",
                "code" => "ALA",
                "dial_code" => "358",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Albania",
                "code" => "ALB",
                "dial_code" => "355",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Algeria",
                "dial_code" => "213",
                "code" => "DZA",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "American Samoa",
                "code" => "ASM",
                "dial_code" => "1684",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Andorra",
                "code" => "AND",
                "dial_code" => "376",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Angola",
                "code" => "AGO",
                "dial_code" => "244",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Anguilla",
                "code" => "AIA",
                "dial_code" => "1264",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Antarctica",
                "code" => "ATA",
                "dial_code" => "672",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Antigua and Barbuda",
                "code" => "ATG",
                "dial_code" => "1268",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Argentina",
                "code" => "ARG",
                "dial_code" => "54",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Armenia",
                "code" => "ARM",
                "dial_code" => "374",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Aruba",
                "code" => "ABW",
                "dial_code" => "297",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Australia",
                "code" => "AUS",
                "dial_code" => "61",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Austria",
                "code" => "AUT",
                "dial_code" => "43",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Azerbaijan",
                "code" => "AZE",
                "dial_code" => "994",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bahamas",
                "code" => "BHS",
                "dial_code" => "1242",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bahrain",
                "code" => "BHR",
                "dial_code" => "973",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bangladesh",
                "code" => "BGD",
                "dial_code" => "880",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Barbados",
                "code" => "BRB",
                "dial_code" => "1246",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Belarus",
                "code" => "BLR",
                "dial_code" => "375",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s') 
            ],
            [
                "name" => "Belgium",
                "code" => "BEL",
                "dial_code" => "32",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Belize",
                "code" => "BLZ",
                "dial_code" => "501",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Benin",
                "code" => "BEN",
                "dial_code" => "229",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bermuda",
                "code" => "BMU",
                "dial_code" => "1441",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bhutan",
                "code" => "BTN",
                "dial_code" => "975",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bolivia",
                "code" => "BOL",
                "dial_code" => "591",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bonaire,Sint Eustatius and Saba",
                "code" => "BES",
                "dial_code" => "599",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bosnia and Herzegovina",
                "code" => "BIH",
                "dial_code" => "387",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Botswana",
                "code" => "BWA",
                "dial_code" => "267",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bouvet Island",
                "code" => "BVT",
                "dial_code" => "55",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Brazil",
                "code" => "BRA",
                "dial_code" => "55",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "British Indian Ocean Territory",
                "code" => "IOT",
                "dial_code" => "246",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Brunei Darussalam",
                "code" => "BRN",
                "dial_code" => "673",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Bulgaria",
                "code" => "BGR",
                "dial_code" => "359",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Burkina Faso",
                "code" => "BFA",
                "dial_code" => "226",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Burundi",
                "code" => "BDI",
                "dial_code" => "257",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Cambodia",
                "code" => "KHM",
                "dial_code" => "855",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Cameroon",
                "code" => "CMR",
                "dial_code" => "237",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Canada",
                "code" => "CAN",
                "dial_code" => "1",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Cape Verde",
                "code" => "CPV",
                "dial_code" => "238",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Cayman Islands",
                "code" => "CYM",
                "dial_code" => "1345",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Central African Republic",
                "code" => "CAF",
                "dial_code" => "236",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Chad",
                "code" => "TCD",
                "dial_code" => "235",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Chile",
                "code" => "CHL",
                "dial_code" => "56",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "China",
                "code" => "CHN",
                "dial_code" => "86",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Christmas Island",
                "code" => "CXR",
                "dial_code" => "61",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Cocos (Keeling) Islands",
                "code" => "CCK",
                "dial_code" => "672",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Colombia",
                "code" => "COL",
                "dial_code" => "57",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Comoros",
                "code" => "COM",
                "dial_code" => "269",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Congo",
                "code" => "COG",
                "dial_code" => "242",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Congo,Democratic Republic of the Congo",
                "code" => "COD",
                "dial_code" => "242",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Cook Islands",
                "code" => "COK",
                "dial_code" => "682",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Costa Rica",
                "code" => "CRI",
                "dial_code" => "506",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Cote D'Ivoire",
                "code" => "CIV",
                "dial_code" => "225",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Croatia",
                "code" => "HRV",
                "dial_code" => "385",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Cuba",
                "code" => "CUB",
                "dial_code" => "53",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Curacao",
                "code" => "CUW",
                "dial_code" => "599",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Cyprus",
                "code" => "CYP",
                "dial_code" => "357",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Czech Republic",
                "code" => "CZE",
                "dial_code" => "420",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Denmark",
                "code" => "DNK",
                "dial_code" => "45",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Djibouti",
                "code" => "DJI",
                "dial_code" => "253",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Dominica",
                "code" => "DMA",
                "dial_code" => "1767",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Dominican Republic",
                "code" => "DOM",
                "dial_code" => "1809",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Ecuador",
                "code" => "ECU",
                "dial_code" => "593",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Egypt",
                "code" => "EGY",
                "dial_code" => "20",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "El Salvador",
                "code" => "SLV",
                "dial_code" => "503",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Equatorial Guinea",
                "code" => "GNQ",
                "dial_code" => "240",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Eritrea",
                "code" => "ERI",
                "dial_code" => "291",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Estonia",
                "code" => "EST",
                "dial_code" => "372",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Ethiopia",
                "code" => "ETH",
                "dial_code" => "251",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Falkland Islands (Malvinas)",
                "code" => "FLK",
                "dial_code" => "500",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Faroe Islands",
                "code" => "FRO",
                "dial_code" => "298",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Fiji",
                "code" => "FJI",
                "dial_code" => "679",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Finland",
                "code" => "FIN",
                "dial_code" => "358",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "France",
                "code" => "FRA",
                "dial_code" => "33",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "French Guiana",
                "code" => "GUF",
                "dial_code" => "594",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "French Polynesia",
                "code" => "PYF",
                "dial_code" => "689",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "French Southern Territories",
                "code" => "ATF",
                "dial_code" => "262",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Gabon",
                "code" => "GAB",
                "dial_code" => "241",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Gambia",
                "code" => "GMB",
                "dial_code" => "220",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Georgia",
                "code" => "GEO",
                "dial_code" => "995",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Germany",
                "code" => "DEU",
                "dial_code" => "49",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Ghana",
                "code" => "GHA",
                "dial_code" => "233",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Gibraltar",
                "code" => "GIB",
                "dial_code" => "350",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Greece",
                "code" => "GRC",
                "dial_code" => "30",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Greenland",
                "code" => "GRL",
                "dial_code" => "299",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Grenada",
                "code" => "GRD",
                "dial_code" => "1473",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Guadeloupe",
                "code" => "GLP",
                "dial_code" => "590",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Guam",
                "code" => "GUM",
                "dial_code" => "1671",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Guatemala",
                "code" => "GTM",
                "dial_code" => "502",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Guernsey",
                "code" => "GGY",
                "dial_code" => "44",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Guinea",
                "code" => "GIN",
                "dial_code" => "224",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Guinea-Bissau",
                "code" => "GNB",
                "dial_code" => "245",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Guyana",
                "code" => "GUY",
                "dial_code" => "592",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Haiti",
                "code" => "HTI",
                "dial_code" => "509",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Heard Island and Mcdonald Islands",
                "code" => "HMD",
                "dial_code" => "0",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Holy See (Vatican City State)",
                "code" => "VAT",
                "dial_code" => "39",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Honduras",
                "code" => "HND",
                "dial_code" => "504",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Hong Kong",
                "code" => "HKG",
                "dial_code" => "852",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Hungary",
                "code" => "HUN",
                "dial_code" => "36",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Iceland",
                "code" => "ISL",
                "dial_code" => "354",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "India",
                "code" => "IND",
                "dial_code" => "91",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Indonesia",
                "code" => "IDN",
                "dial_code" => "62",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Iran,Islamic Republic of",
                "code" => "IRN",
                "dial_code" => "98",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Iraq",
                "code" => "IRQ",
                "dial_code" => "964",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Ireland",
                "code" => "IRL",
                "dial_code" => "353",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Isle of Man",
                "code" => "IMN",
                "dial_code" => "44",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Israel",
                "code" => "ISR",
                "dial_code" => "972",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Italy",
                "code" => "ITA",
                "dial_code" => "39",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Jamaica",
                "code" => "JAM",
                "dial_code" => "1876",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Japan",
                "code" => "JPN",
                "dial_code" => "81",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Jersey",
                "code" => "JEY",
                "dial_code" => "44",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Jordan",
                "code" => "JOR",
                "dial_code" => "962",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Kazakhstan",
                "code" => "KAZ",
                "dial_code" => "7",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Kenya",
                "code" => "KEN",
                "dial_code" => "254",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Kiribati",
                "code" => "KIR",
                "dial_code" => "686",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Korea,Democratic People's Republic of",
                "code" => "PRK",
                "dial_code" => "850",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Korea,Republic of",
                "code" => "KOR",
                "dial_code" => "82",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Kosovo",
                "code" => "XKX",
                "dial_code" => "381",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Kuwait",
                "code" => "KWT",
                "dial_code" => "965",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Kyrgyzstan",
                "code" => "KGZ",
                "dial_code" => "996",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Lao People's Democratic Republic",
                "code" => "LAO",
                "dial_code" => "856",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Latvia",
                "code" => "LVA",
                "dial_code" => "371",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Lebanon",
                "code" => "LBN",
                "dial_code" => "961",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Lesotho",
                "code" => "LSO",
                "dial_code" => "266",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Liberia",
                "code" => "LBR",
                "dial_code" => "231",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Libyan Arab Jamahiriya",
                "code" => "LBY",
                "dial_code" => "218",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Liechtenstein",
                "code" => "LIE",
                "dial_code" => "423",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Lithuania",
                "code" => "LTU",
                "dial_code" => "370",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Luxembourg",
                "code" => "LUX",
                "dial_code" => "352",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Macao",
                "code" => "MAC",
                "dial_code" => "853",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Macedonia,the Former Yugoslav Republic of",
                "code" => "MKD",
                "dial_code" => "389",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Madagascar",
                "code" => "MDG",
                "dial_code" => "261",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Malawi",
                "code" => "MWI",
                "dial_code" => "265",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Malaysia",
                "code" => "MYS",
                "dial_code" => "60",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Maldives",
                "code" => "MDV",
                "dial_code" => "960",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Mali",
                "code" => "MLI",
                "dial_code" => "223",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Malta",
                "code" => "MLT",
                "dial_code" => "356",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Marshall Islands",
                "code" => "MHL",
                "dial_code" => "692",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Martinique",
                "code" => "MTQ",
                "dial_code" => "596",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Mauritania",
                "code" => "MRT",
                "dial_code" => "222",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Mauritius",
                "code" => "MUS",
                "dial_code" => "230",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Mayotte",
                "code" => "MYT",
                "dial_code" => "269",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Mexico",
                "code" => "MEX",
                "dial_code" => "52",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Micronesia,Federated States of",
                "code" => "FSM",
                "dial_code" => "691",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Moldova,Republic of",
                "code" => "MDA",
                "dial_code" => "373",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Monaco",
                "code" => "MCO",
                "dial_code" => "377",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Mongolia",
                "code" => "MNG",
                "dial_code" => "976",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Montenegro",
                "code" => "MNE",
                "dial_code" => "382",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Montserrat",
                "code" => "MSR",
                "dial_code" => "1664",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Morocco",
                "code" => "MAR",
                "dial_code" => "212",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Mozambique",
                "code" => "MOZ",
                "dial_code" => "258",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Myanmar",
                "code" => "MMR",
                "dial_code" => "95",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Namibia",
                "code" => "NAM",
                "dial_code" => "264",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Nauru",
                "code" => "NRU",
                "dial_code" => "674",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Nepal",
                "code" => "NPL",
                "dial_code" => "977",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Netherlands",
                "code" => "NLD",
                "dial_code" => "31",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Netherlands Antilles",
                "code" => "ANT",
                "dial_code" => "599",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "New Caledonia",
                "code" => "NCL",
                "dial_code" => "687",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "New Zealand",
                "code" => "NZL",
                "dial_code" => "64",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Nicaragua",
                "code" => "NIC",
                "dial_code" => "505",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Niger",
                "code" => "NER",
                "dial_code" => "227",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Nigeria",
                "code" => "NGA",
                "dial_code" => "234",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Niue",
                "code" => "NIU",
                "dial_code" => "683",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Norfolk Island",
                "code" => "NFK",
                "dial_code" => "672",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Northern Mariana Islands",
                "code" => "MNP",
                "dial_code" => "1670",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Norway",
                "code" => "NOR",
                "dial_code" => "47",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Oman",
                "code" => "OMN",
                "dial_code" => "968",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Pakistan",
                "code" => "PAK",
                "dial_code" => "92",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Palau",
                "code" => "PLW",
                "dial_code" => "680",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Palestinian Territory,Occupied",
                "code" => "PSE",
                "dial_code" => "970",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Panama",
                "code" => "PAN",
                "dial_code" => "507",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Papua New Guinea",
                "code" => "PNG",
                "dial_code" => "675",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Paraguay",
                "code" => "PRY",
                "dial_code" => "595",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Peru",
                "code" => "PER",
                "dial_code" => "51",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Philippines",
                "code" => "PHL",
                "dial_code" => "63",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Pitcairn",
                "code" => "PCN",
                "dial_code" => "64",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Poland",
                "code" => "POL",
                "dial_code" => "48",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Portugal",
                "code" => "PRT",
                "dial_code" => "351",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Puerto Rico",
                "code" => "PRI",
                "dial_code" => "1787",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Qatar",
                "code" => "QAT",
                "dial_code" => "974",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Reunion",
                "code" => "REU",
                "dial_code" => "262",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Romania",
                "code" => "ROM",
                "dial_code" => "40",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Russian Federation",
                "code" => "RUS",
                "dial_code" => "70",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Rwanda",
                "code" => "RWA",
                "dial_code" => "250",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Saint Barthelemy",
                "code" => "BLM",
                "dial_code" => "590",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Saint Helena",
                "code" => "SHN",
                "dial_code" => "290",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Saint Kitts and Nevis",
                "code" => "KNA",
                "dial_code" => "1869",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Saint Lucia",
                "code" => "LCA",
                "dial_code" => "1758",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Saint Martin",
                "code" => "MAF",
                "dial_code" => "590",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Saint Pierre and Miquelon",
                "code" => "SPM",
                "dial_code" => "508",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Saint Vincent and the Grenadines",
                "code" => "VCT",
                "dial_code" => "1784",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Samoa",
                "code" => "WSM",
                "dial_code" => "684",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "San Marino",
                "code" => "SMR",
                "dial_code" => "378",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Sao Tome and Principe",
                "code" => "STP",
                "dial_code" => "239",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Saudi Arabia",
                "code" => "SAU",
                "dial_code" => "966",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Senegal",
                "code" => "SEN",
                "dial_code" => "221",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Serbia",
                "code" => "SRB",
                "dial_code" => "381",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Serbia and Montenegro",
                "code" => "SCG",
                "dial_code" => "381",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Seychelles",
                "code" => "SYC",
                "dial_code" => "248",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Sierra Leone",
                "code" => "SLE",
                "dial_code" => "232",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Singapore",
                "code" => "SGP",
                "dial_code" => "65",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Sint Maarten",
                "code" => "SXM",
                "dial_code" => "1",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Slovakia",
                "code" => "SVK",
                "dial_code" => "421",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Slovenia",
                "code" => "SVN",
                "dial_code" => "386",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Solomon Islands",
                "code" => "SLB",
                "dial_code" => "677",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Somalia",
                "code" => "SOM",
                "dial_code" => "252",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "South Africa",
                "code" => "ZAF",
                "dial_code" => "27",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "South Georgia and the South Sandwich Islands",
                "code" => "SGS",
                "dial_code" => "500",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "South Sudan",
                "code" => "SSD",
                "dial_code" => "211",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Spain",
                "code" => "ESP",
                "dial_code" => "34",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Sri Lanka",
                "code" => "LKA",
                "dial_code" => "94",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Sudan",
                "code" => "SDN",
                "dial_code" => "249",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Suriname",
                "code" => "SUR",
                "dial_code" => "597",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Svalbard and Jan Mayen",
                "code" => "SJM",
                "dial_code" => "47",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Swaziland",
                "code" => "SWZ",
                "dial_code" => "268",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Sweden",
                "code" => "SWE",
                "dial_code" => "46",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Switzerland",
                "code" => "CHE",
                "dial_code" => "41",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Syrian Arab Republic",
                "code" => "SYR",
                "dial_code" => "963",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Taiwan,Province of China",
                "code" => "TWN",
                "dial_code" => "886",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Tajikistan",
                "code" => "TJK",
                "dial_code" => "992",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Tanzania,United Republic of",
                "code" => "TZA",
                "dial_code" => "255",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Thailand",
                "code" => "THA",
                "dial_code" => "66",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Timor-Leste",
                "code" => "TLS",
                "dial_code" => "670",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Togo",
                "code" => "TGO",
                "dial_code" => "228",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Tokelau",
                "code" => "TKL",
                "dial_code" => "690",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Tonga",
                "code" => "TON",
                "dial_code" => "676",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Trinidad and Tobago",
                "code" => "TTO",
                "dial_code" => "1868",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Tunisia",
                "code" => "TUN",
                "dial_code" => "216",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Turkey",
                "code" => "TUR",
                "dial_code" => "90",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Turkmenistan",
                "code" => "TKM",
                "dial_code" => "7370",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Turks and Caicos Islands",
                "code" => "TCA",
                "dial_code" => "1649",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Tuvalu",
                "code" => "TUV",
                "dial_code" => "688",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Uganda",
                "code" => "UGA",
                "dial_code" => "256",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Ukraine",
                "code" => "UKR",
                "dial_code" => "380",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "United Arab Emirates",
                "code" => "ARE",
                "dial_code" => "971",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "United Kingdom",
                "code" => "GBR",
                "dial_code" => "44",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "United States",
                "code" => "USA",
                "dial_code" => "1",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "United States Minor Outlying Islands",
                "code" => "UMI",
                "dial_code" => "1",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Uruguay",
                "code" => "URY",
                "dial_code" => "598",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Uzbekistan",
                "code" => "UZB",
                "dial_code" => "998",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Vanuatu",
                "code" => "VUT",
                "dial_code" => "678",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Venezuela",
                "code" => "VEN",
                "dial_code" => "58",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Viet Nam",
                "code" => "VNM",
                "dial_code" => "84",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Virgin Islands,British",
                "code" => "VGB",
                "dial_code" => "1284",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Virgin Islands,U.s.",
                "code" => "VIR",
                "dial_code" => "1340",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Wallis and Futuna",
                "code" => "WLF",
                "dial_code" => "681",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Western Sahara",
                "code" => "ESH",
                "dial_code" => "212",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Yemen",
                "code" => "YEM",
                "dial_code" => "967",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Zambia",
                "code" => "ZMB",
                "dial_code" => "260",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ],
            [
                "name" => "Zimbabwe",
                "code" => "ZWE",
                "dial_code" => "263",
                "status" => 1,
                "created_datetime" => date('Y-m-d H:i:s')
            ]
        ];
        $this->db->table('country')->insertBatch($country_data);
    }
}
