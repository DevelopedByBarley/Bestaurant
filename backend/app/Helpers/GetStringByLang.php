<?php
function getStringByLang($titleInHu, $titleInEn)
{
  $lang = $_COOKIE["lang"] ?? null;

  if ($lang === "Hu") {
    return $titleInHu;
  } else {
    return $titleInEn;
  }
}
