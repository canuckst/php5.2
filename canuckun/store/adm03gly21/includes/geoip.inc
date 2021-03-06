<?php

/* based on php version of the geoip library written in may 2002
   by jim winstead <jimw@apache.org> */

/* -*- Mode: C; indent-tabs-mode: t; c-basic-offset: 2; tab-width: 2 -*- */
/* geoip.inc
 *
 * Copyright (C) 2002 MaxMind
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$GEOIP_COUNTRY_CODES = array(
"", "AP", "EU", "AD", "AE", "AF", "AG", "AI", "AL", "AM", "AN", "AO", "AQ",
"AR", "AS", "AT", "AU", "AW", "AZ", "BA", "BB", "BD", "BE", "BF", "BG", "BH",
"BI", "BJ", "BM", "BN", "BO", "BR", "BS", "BT", "BV", "BW", "BY", "BZ", "CA",
"CC", "CD", "CF", "CG", "CH", "CI", "CK", "CL", "CM", "CN", "CO", "CR", "CU",
"CV", "CX", "CY", "CZ", "DE", "DJ", "DK", "DM", "DO", "DZ", "EC", "EE", "EG",
"EH", "ER", "ES", "ET", "FI", "FJ", "FK", "FM", "FO", "FR", "FX", "GA", "GB",
"GD", "GE", "GF", "GH", "GI", "GL", "GM", "GN", "GP", "GQ", "GR", "GS", "GT",
"GU", "GW", "GY", "HK", "HM", "HN", "HR", "HT", "HU", "ID", "IE", "IL", "IN",
"IO", "IQ", "IR", "IS", "IT", "JM", "JO", "JP", "KE", "KG", "KH", "KI", "KM",
"KN", "KP", "KR", "KW", "KY", "KZ", "LA", "LB", "LC", "LI", "LK", "LR", "LS",
"LT", "LU", "LV", "LY", "MA", "MC", "MD", "MG", "MH", "MK", "ML", "MM", "MN",
"MO", "MP", "MQ", "MR", "MS", "MT", "MU", "MV", "MW", "MX", "MY", "MZ", "NA",
"NC", "NE", "NF", "NG", "NI", "NL", "NO", "NP", "NR", "NU", "NZ", "OM", "PA",
"PE", "PF", "PG", "PH", "PK", "PL", "PM", "PN", "PR", "PS", "PT", "PW", "PY",
"QA", "RE", "RO", "RU", "RW", "SA", "SB", "SC", "SD", "SE", "SG", "SH", "SI",
"SJ", "SK", "SL", "SM", "SN", "SO", "SR", "ST", "SV", "SY", "SZ", "TC", "TD",
"TF", "TG", "TH", "TJ", "TK", "TM", "TN", "TO", "TP", "TR", "TT", "TV", "TW",
"TZ", "UA", "UG", "UM", "US", "UY", "UZ", "VA", "VC", "VE", "VG", "VI", "VN",
"VU", "WF", "WS", "YE", "YT", "YU", "ZA", "ZM", "ZR", "ZW"
);

$GEOIP_COUNTRY_CODES3 = array (
"","AP","EU","AND","ARE","AFG","ATG","AIA","ALB","ARM","ANT","AGO","AQ","ARG",
"ASM","AUT","AUS","ABW","AZE","BIH","BRB","BGD","BEL","BFA","BGR","BHR","BDI",
"BEN","BMU","BRN","BOL","BRA","BHS","BTN","BV","BWA","BLR","BLZ","CAN","CC",
"COD","CAF","COG","CHE","CIV","COK","CHL","CMR","CHN","COL","CRI","CUB","CPV",
"CX","CYP","CZE","DEU","DJI","DNK","DMA","DOM","DZA","ECU","EST","EGY","ESH",
"ERI","ESP","ETH","FIN","FJI","FLK","FSM","FRO","FRA","FX","GAB","GBR","GRD",
"GEO","GUF","GHA","GIB","GRL","GMB","GIN","GLP","GNQ","GRC","GS","GTM","GUM",
"GNB","GUY","HKG","HM","HND","HRV","HTI","HUN","IDN","IRL","ISR","IND","IO",
"IRQ","IRN","ISL","ITA","JAM","JOR","JPN","KEN","KGZ","KHM","KIR","COM","KNA",
"PRK","KOR","KWT","CYM","KAZ","LAO","LBN","LCA","LIE","LKA","LBR","LSO","LTU",
"LUX","LVA","LBY","MAR","MCO","MDA","MDG","MHL","MKD","MLI","MMR","MNG","MAC",
"MNP","MTQ","MRT","MSR","MLT","MUS","MDV","MWI","MEX","MYS","MOZ","NAM","NCL",
"NER","NFK","NGA","NIC","NLD","NOR","NPL","NRU","NIU","NZL","OMN","PAN","PER",
"PYF","PNG","PHL","PAK","POL","SPM","PCN","PRI","PSE","PRT","PLW","PRY","QAT",
"REU","ROU","RUS","RWA","SAU","SLB","SYC","SDN","SWE","SGP","SHN","SVN","SJM",
"SVK","SLE","SMR","SEN","SOM","SUR","STP","SLV","SYR","SWZ","TCA","TCD","TF",
"TGO","THA","TJK","TKL","TLS","TKM","TUN","TON","TUR","TTO","TUV","TWN","TZA",
"UKR","UGA","UM","USA","URY","UZB","VAT","VCT","VEN","VGB","VIR","VNM","VUT",
"WLF","WSM","YEM","YT","YUG","ZAF","ZMB","ZR","ZWE","A1","A2","O1");

define("GEOIP_COUNTRY_BEGIN", 16776960);
define("GEOIP_STATE_BEGIN", 16700000);
define("GEOIP_STANDARD", 0);
define("GEOIP_MEMORY_CACHE", 1);
define("STRUCTURE_INFO_MAX_SIZE",20);
define("DATABASE_INFO_MAX_SIZE",100);
define("GEOIP_COUNTRY_EDITION",106);
define("GEOIP_REGION_EDITION",112);
define("GEOIP_CITY_EDITION",111);
define("GEOIP_ORG_EDITION",110);
define("SEGMENT_RECORD_LENGTH",3);
define("STANDARD_RECORD_LENGTH",3);
define("ORG_RECORD_LENGTH",4);
define("MAX_RECORD_LENGTH",4);
define("MAX_ORG_RECORD_LENGTH",300);

class GeoIP {
  var $flags;
  var $filehandle;
  var $memory_buffer;
  var $databaseType;
  var $databaseSegments;
  var $record_length;
}

function _setup_segments($gi){
  $filepos = ftell($gi->filehandle);
  $gi->databaseType = GEOIP_COUNTRY_EDITION;
  $gi->record_length = STANDARD_RECORD_LENGTH;
  fseek($gi->filehandle, -3, SEEK_END);
  for ($i = 0; $i < STRUCTURE_INFO_MAX_SIZE; $i++) {
    $delim = fread($gi->filehandle,3);
    if ($delim == (chr(255).chr(255).chr(255))){
      $gi->databaseType = ord(fread($gi->filehandle,1));
      if ($gi->databaseType == GEOIP_REGION_EDITION){
        $gi->databaseSegments = GEOIP_STATE_BEGIN;
      } else if ($gi->databaseType == GEOIP_CITY_EDITION || $gi->databaseType == GEOIP_ORG_EDITION){
        $gi->databaseSegments = 0;
        $buf = fread($gi->filehandle,SEGMENT_RECORD_LENGTH);
        for ($j = 0;$j < SEGMENT_RECORD_LENGTH;$j++){
          $gi->databaseSegments += (ord($buf[$j]) << ($j * 8));
        }
	if ($gi->databaseType == GEOIP_ORG_EDITION) {
	  $gi->record_length = ORG_RECORD_LENGTH;
        }
      }
      break;
    } else {
      fseek($gi->filehandle, -4, SEEK_CUR);
    }
  }
  if ($gi->databaseType == GEOIP_COUNTRY_EDITION){
    $gi->databaseSegments = GEOIP_COUNTRY_BEGIN;
  }
  fseek($gi->filehandle,$filepos,SEEK_SET);
  return $gi;
}

function geoip_open($filename, $flags) {
  $gi = new GeoIP;
  $gi->flags = $flags;

  $gi->filehandle = fopen($filename,"rb");

  if ($gi->flags & GEOIP_MEMORY_CACHE) {
    $s_array = fstat($gi->filehandle);
    $gi->memory_buffer = fread($gi->filehandle, $s_array[size]);
  }
  $gi = _setup_segments($gi);
  return $gi;
}

function geoip_close($gi) {
  return fclose($gi->filehandle);
}

function geoip_country_id_by_name($gi, $name) {
  $addr = gethostbyname($name);
  if (!$addr || $addr == $name) {
    return false;
  }
  return geoip_country_id_by_addr($gi, $addr);
}

function geoip_country_code_by_name($gi, $name) {
  $country_id = geoip_country_id_by_name($gi,$name);
  if ($country_id !== false) {
    return $GLOBALS['GEOIP_COUNTRY_CODES'][$country_id];
  }
  return false;
}

function geoip_country_name_by_name($gi, $name) {
  $country_id = geoip_country_id_by_name($gi,$name);
  if ($country_id !== false) {
    return $GLOBALS['GEOIP_COUNTRY_NAMES'][$country_id];
  }
  return false;
}

function geoip_country_id_by_addr($gi, $addr) {
  $ipnum = ip2long($addr);
  return _geoip_seek_country($gi, $ipnum) - GEOIP_COUNTRY_BEGIN;
}

function geoip_country_code_by_addr($gi, $addr) {
  $country_id = geoip_country_id_by_addr($gi,$addr);
  if ($country_id !== false) {
    return $GLOBALS['GEOIP_COUNTRY_CODES'][$country_id];
  }
  return false;
}

function geoip_country_name_by_addr($gi, $addr) {
  $country_id = geoip_country_id_by_addr($gi,$addr);
  if ($country_id !== false) {
    return $GLOBALS['GEOIP_COUNTRY_NAMES'][$country_id];
  }
  return false;
}

function _geoip_seek_country($gi, $ipnum) {
  $offset = 0;
  for ($depth = 31; $depth >= 0; --$depth) {
    if ($gi->flags & GEOIP_MEMORY_CACHE) {
      $buf = substr($gi->memory_buffer,2 * $gi->record_length * $offset, 6);
    } else {
      fseek($gi->filehandle, 2 * $gi->record_length * $offset, SEEK_SET) == 0
        or die("fseek failed");
      $buf = fread($gi->filehandle, 2 * $gi->record_length);
    }
    $x = array(0,0);
    for ($i = 0; $i < 2; ++$i) {
      for ($j = 0; $j < $gi->record_length; ++$j) {
        $x[$i] += ord($buf[$gi->record_length * $i + $j]) << ($j * 8);
      }
    }
    if ($ipnum & (1 << $depth)) {
      if ($x[1] >= $gi->databaseSegments) {
        return $x[1];
      }
      $offset = $x[1];
    }
    else {
      if ($x[0] >= $gi->databaseSegments) {
        return $x[0];
      }
      $offset = $x[0];
    }
  }

  trigger_error("error traversing database - perhaps it is corrupt?", E_USER_ERROR);
  return false;
}

function _get_org($gi,$ipnum){
  $seek_org = _geoip_seek_country($gi,$ipnum);
  if ($seek_org == $gi->databaseSegments) {
    return NULL;
  }
  $record_pointer = $seek_org + (2 * $gi->record_length - 1) * $gi->databaseSegments;
  fseek($gi->filehandle, $record_pointer, SEEK_SET);
  $org_buf = fread($gi->filehandle,MAX_ORG_RECORD_LENGTH);
  $org_buf = substr($org_buf, 0, strpos($org_buf, 0));
  return $org_buf;
}

function geoip_org_by_addr ($gi,$addr) {
  if ($addr == NULL) {
    return 0;
  }
  $ipnum = ip2long($addr);
  return _get_org($gi, $ipnum);
}

function _get_region($gi,$ipnum){
  $seek_region = _geoip_seek_country($gi,$ipnum) - GEOIP_STATE_BEGIN;
  if ($seek_region >= 1000){
    $country_code = "US";
    $region = chr(($seek_region - 1000)/26 + 65) . chr(($seek_region - 1000)%26 + 65);
  } else {
    $country_code = $GLOBALS['GEOIP_COUNTRY_CODES'][$seek_region];
    $region = "";
  }
  return array ($country_code,$region);
}

function geoip_region_by_addr ($gi,$addr) {
  if ($addr == NULL) {
    return 0;
  }
  $ipnum = ip2long($addr);
  return _get_region($gi, $ipnum);
}

?>
