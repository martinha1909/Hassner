<?php
if(!defined('MENUOPTION_LOADED')){
    abstract class MenuOption
    {
        //starter
        const None = "NONE";

        //listener side
        const Portfolio = "PORTFOLIO";

        //artist side
        const Ethos = "ETHOS";

        //both 
        const Artists = "ARTISTS";
        const Siliqas = "SILIQAS";
        const Account = "ACCOUNT";
        const Campaign = "CAMPAIGN";
    }
    define('MENUOPTION_LOADED', 1);
}
?>