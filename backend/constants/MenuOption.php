<?php
if(!defined('MENUOPTION_LOADED')){
    abstract class MenuOption
    {
        //starter
        const None = "NONE";

        //listener side
        const Portfolio = "PORTFOLIO";
        const Artists = "ARTISTS";

        //artist side
        const Ethos = "ETHOS";
        const Investors = "INVESTORS";

        //both 
        const Balance = "BALANCE";
        const Account = "ACCOUNT";
        const Campaign = "CAMPAIGN";
    }
    define('MENUOPTION_LOADED', 1);
}
?>