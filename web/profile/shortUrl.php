<?php
                include_once(JsConstants::$docRoot."/classes/ShortURL.class.php");
                        $longURL = JsConstants::$siteUrl."/".$_GET['url'];
                $shortURL = new ShortURL();
                $SHORT_URL = $shortURL->setShortURL($longURL);
                echo $SHORT_URL . "\n";
                die;

