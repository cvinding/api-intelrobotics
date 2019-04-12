<?php

namespace MODEL;


class UserModel {

    public function getLDAPUserInfo(string $searchInput) : array {
        $config = require "../config/ldap.php";

        $username = $config["LDAP_USERNAME"];
        $password = $config["LDAP_PASSWORD"];

        $hostname = "ldap://ad.intelrobotics.dk";

        $ldapConn = ldap_connect($hostname);

        $ldapRDN = 'AD' . "\\" . $username;

        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConn, LDAP_OPT_X_TLS_REQUIRE_CERT, 0);
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

        //TODO: create fronter like
        if(!ldap_start_tls($ldapConn)) {
            var_dump("LDAP TLS connection failed");
        }

        $ldapBind = ldap_bind($ldapConn, $ldapRDN, $password);

        $output = [];

        if($ldapBind) {

            $ldapBaseDN = "OU=IntelRobotics,DC=ad,DC=intelrobotics,DC=dk";
            $search = "(&(objectCategory=person)(cn=*{$searchInput}*))";
            $result = ldap_search($ldapConn, $ldapBaseDN, $search);

            $entries = ldap_get_entries($ldapConn, $result);

            $bannedGroups = ["DK_","JP_","CA_","IntelRobotics_"];

            for($u = 0; $u < $entries["count"]; $u++) {

                $fakeUser = false;

                for($i = 0; $i < $entries[$u]["memberof"]["count"]; $i++ ) {
                    if(strpos($entries[$u]["memberof"][$i], "ServiceUser_SG") !== false) {
                        $fakeUser = true;
                        break;
                    }
                }

                for($i = 0; $i < $entries[$u]["givenname"]["count"]; $i++ ) {
                    if(strpos($entries[$u]["givenname"][$i], "Template") !== false) {
                        $fakeUser = true;
                        break;
                    }
                }

                if($fakeUser) {
                    continue;
                }

                $displayGroups = [];

                for($i = 0; $i < $entries[$u]["memberof"]["count"]; $i++ ) {
                    $cn = explode("=",explode(",", $entries[$u]["memberof"][$i])[0])[1];

                    $dontAdd = false;

                    foreach($bannedGroups as $group) {
                        if(strpos($cn, $group) !== false) {
                            $dontAdd = true;
                        }
                    }

                    if(!$dontAdd) {
                        if($cn === "Denmark_SG" || $cn === "Canada_SG" || $cn === "Japan_SG") {
                            $displayGroups["country"] = str_replace("_SG", "", $cn);
                        }else {
                            $displayGroups["department"] = str_replace("_SG", "", $cn);
                        }
                    }
                }

                $temp = ["fullname" => $entries[$u]["displayname"][0], "social_security_number" => "020280-8905"];

                $temp = array_merge($temp, $displayGroups);

                $output[] = $temp;

            }
        }
    }

}