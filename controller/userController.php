<?php
namespace CONTROLLER;


class UserController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    public function __construct() {
        parent::__construct([
            "searchPhoneBook" => [
                "REQUEST_METHOD_LEVEL" => 1,
                "TOKEN" => true
            ],
            "searchUserList" => [
                "REQUEST_METHOD_LEVEL" => 1,
                "TOKEN" => true,
                "PERMISSIONS" => [
                    "Administration_SG"
                ]
            ]
        ]);
    }


    public function searchPhoneBook(string $searchInput) {
        //TODO: used by everyone to search for user + other work related info
    }

    public function searchUserList(string $searchInput) {
        //TODO: used by HR to search for user + CPR number


        /*
        $username = "API_LDAP_SEARCHER";
        $password = "IntelRobotics!";

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

        if($ldapBind) {

            $ldapBaseDN = "OU=IntelRobotics,DC=ad,DC=intelrobotics,DC=dk";
            $search = "(&(objectCategory=person))";
            $result = ldap_search($ldapConn, $ldapBaseDN, $search);

            $entries = ldap_get_entries($ldapConn, $result);

            //var_dump($entries);

            //TODO: check other memberof entries for ServiceUser_SG
            foreach($entries as $entry) {
                if(strpos($entry["givenname"][0], "Template") === false && strpos($entry["memberof"][0], "ServiceUser_SG") === false) {
                  var_dump($entry);
                }
            }
        }

        */
        exit(json_encode(["result" => $searchInput, "status" => true]));
    }


}